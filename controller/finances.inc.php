<?php

require_once('model/pages.php');
require_once('model/ranks.php');
require_once('model/finances.php');
require_once('model/inventories.php');
require_once('model/sold.php');
require_once('model/productsizes.php');
require_once('model/sizes.php');
require_once('model/members.php');
require_once('model/users.php');
require_once('model/contributions.php');
require_once('config/config.inc.php');

require_once(ROOT.'vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FinancesController
{
    public function get_afficher()
    {
        $page = Pages::getByUuid($_GET['page']);
        $rank = Ranks::getByUserAndPage($_SESSION['user'], $page->id());
        $finances = Finances::getAllByPage($page->id());
        $amounts = Finances::getCaisseByPage($page->id());
        $recettes_totales = $amounts[0];
        $caisse = $amounts[1];
        $apport = $amounts[2];
        $reel_gagne = $amounts[3];
        $inventory = Inventories::getAllByPage($page->id());
        $members = Members::getAllByPage($page->id());
        include 'view/finances/afficher.inc.php';
    }

    public function post_entree()
    {
        //Merch
        if ($_POST['form_type'] == 0)
        {
            $quantity = $_POST['quantity'];
            $is_numeric_and_set = true;
            for ($i = 0; $i < sizeof($quantity); $i++)
            {
                if ($quantity[$i] == null || $quantity[$i] == '' || !is_numeric($quantity[$i]))
                    $is_numeric_and_set = false;
            }

            if ($is_numeric_and_set)
            {
                $description = '';
                for ($i = 0; $i < sizeof($quantity); $i++)
                {
                    if ($quantity[$i] > 0)
                    {
                        if ($i > 0 && $description != '')
                            $description .= ' + ';

                        $inv = $_POST['product'][$i];
                        $size = null;

                        if (stripos($_POST['product'][$i], '_') !== false)
                        {
                            $product = explode('_', $_POST['product'][$i]);
                            $inv = $product[0];
                            $size = $product[1];
                        }
                        $inv_name = Inventories::getById($inv)->description();
                        $size_name = '';
                        if ($size != null)
                            $size_name = ' Taille '.Sizes::getById(ProductSizes::getById($size)->id_size())->label();
                        $description .= $quantity[$i].' '.$inv_name.$size_name;
                    }
                }

                if ($description != '')
                {
                    $amount = str_replace(',', '.', $_POST['montant']);
                    if (stripos($amount, '.') !== false)
                    {
                        $int = explode('.', $amount)[0];
                        $dec = explode('.', $amount)[1];
                        if (strlen($dec) == 1)
                            $dec .= 0;
                        $amount = $int.'.'.$dec;
                    }
                    $amount = $amount * 100;

                    if (isset($_POST['description_libre_merch']) && $_POST['description_libre_merch'] != null)
                        $description .= ' ('.$_POST['description_libre_merch'].')';

                    $finance = Finances::insertFinance(Pages::getByUuid($_GET['page'])->id(), 1, $description, $amount, date('Y-m-d H:i:s'));
                    //On met à jour les stocks
                    for ($i = 0; $i < sizeof($quantity); $i++)
                    {
                        if ($quantity[$i] > 0)
                        {
                            $inv = $_POST['product'][$i];
                            $size = null;

                            if (stripos($_POST['product'][$i], '_') !== false)
                            {
                                $product = explode('_', $_POST['product'][$i]);
                                $inv = $product[0];
                                $size = $product[1];
                            }
                            
                            if ($size == null)
                            {
                                $inventory = Inventories::getById($inv);
                                $stock = $inventory->stock() - $quantity[$i];
                                $inventory->modifyInventory($inventory->description(), $inventory->price(), $stock);
                                if ($stock == 0)
                                    $inventory->disableInventory();
                                $inventory->save();
                                $inventoryId = $inventory->id();
                                $isGarment = 0;
                            }
                            else
                            {
                                $productSize = ProductSizes::getById($size);
                                $stock = $productSize->stock() - $quantity[$i];
                                $productSize->modifyProductSIze($stock);
                                $productSize->save();
                                $inventoryId = $productSize->id();
                                $isGarment = 1;
                            }

                            //On rajoute dans les produits vendus
                            Sold::insertSold($finance->id(), $inventoryId, $quantity[$i], $isGarment);
                        }
                    }
                    $_SESSION['success_message'] = "L'entrée d'argent a bien été ajoutée.";
                    header('Location:index.php?page='.$_GET['page'].'&ctrl=finances&action=afficher');
                    exit;
                }
                else
                {
                    $_SESSION['error_message'] = "Veuillez renseigner au moins un élément vendu.";
                    header('Location:index.php?page='.$_GET['page'].'&ctrl=finances&action=afficher');
                    exit;
                }
            }
            else
            {
                $_SESSION['error_message'] = "Veuillez correctement renseigner tous les champs requis.";
                header('Location:index.php?page='.$_GET['page'].'&ctrl=finances&action=afficher');
                exit;
            }
        }
        //Autre
        else
        {
            $amount = (isset($_POST['montant_libre'])) ? $_POST['montant_libre'] : null;
            $description = (isset($_POST['description_libre'])) ? $_POST['description_libre'] : null;

            if ($amount != null && $description != null)
            {
                if (is_numeric($amount))
                {
                    $amount = str_replace(',', '.', $amount);
                    if (stripos($amount, '.') !== false)
                    {
                        $int = explode('.', $amount)[0];
                        $dec = explode('.', $amount)[1];
                        if (strlen($dec) == 1)
                            $dec .= 0;
                        $amount = $int.'.'.$dec;
                    }
                    $amount = $amount * 100;

                    Finances::insertFinance(Pages::getByUuid($_GET['page'])->id(), 1, $description, $amount, date('Y-m-d H:i:s'));
                    $_SESSION['success_message'] = "L'entrée d'argent a bien été ajoutée.";
                    header('Location:index.php?page='.$_GET['page'].'&ctrl=finances&action=afficher');
                    exit;
                }
                else
                {
                    $_SESSION['error_message'] = "Le montant doit être un chiffre ou un nombre à virgule.";
                    header('Location:index.php?page='.$_GET['page'].'&ctrl=finances&action=afficher');
                    exit;
                }
            }
            else
            {
                $_SESSION['error_message'] = "Veuillez renseigner tous les champs demandés.";
                header('Location:index.php?page='.$_GET['page'].'&ctrl=finances&action=afficher');
                exit;
            }
        }
    }

    public function post_sortie()
    {
        $amount = (isset($_POST['montant_sortie_libre'])) ? $_POST['montant_sortie_libre'] : null;
        $description = (isset($_POST['description_sortie_libre'])) ? $_POST['description_sortie_libre'] : null;

        if ($amount != null && $description != null)
        {
            if (is_numeric($amount))
            {
                $amount = str_replace(',', '.', $amount);
                if (stripos($amount, '.') !== false)
                {
                    $int = explode('.', $amount)[0];
                    $dec = explode('.', $amount)[1];
                    if (strlen($dec) == 1)
                        $dec .= 0;
                    $amount = $int.'.'.$dec;
                }
                $amount = $amount * 100;

                Finances::insertFinance(Pages::getByUuid($_GET['page'])->id(), 0, $description, $amount, date('Y-m-d H:i:s'));
                $_SESSION['success_message'] = "La sortie d'argent a bien été ajoutée.";
                header('Location:index.php?page='.$_GET['page'].'&ctrl=finances&action=afficher');
                exit;
            }
            else
            {
                $_SESSION['error_message'] = "Le montant doit être un chiffre ou un nombre à virgule.";
                header('Location:index.php?page='.$_GET['page'].'&ctrl=finances&action=afficher');
                exit;
            }
        }
        else
        {
            $_SESSION['error_message'] = "Veuillez renseigner tous les champs demandés.";
            header('Location:index.php?page='.$_GET['page'].'&ctrl=finances&action=afficher');
            exit;
        }
    }

    public function post_apport()
    {
        $amount = (isset($_POST['montant_apport_libre'])) ? $_POST['montant_apport_libre'] : null;
        $members = (isset($_POST['members'])) ? $_POST['members'] : [];

        if ($amount != null && !empty($members))
        {
            if (is_numeric($amount))
            {
                $amount = str_replace(',', '.', $amount);
                if (stripos($amount, '.') !== false)
                {
                    $int = explode('.', $amount)[0];
                    $dec = explode('.', $amount)[1];
                    if (strlen($dec) == 1)
                        $dec .= 0;
                    $amount = $int.'.'.$dec;
                }
                $amount = $amount * 100;

                $description = "Apport de ";

                for ($i = 0; $i < sizeof($members); $i++)
                {
                    if ($i > 0)
                        $description .= ', ';
                    $description .= Users::getById($members[$i])->first_name();
                }

                if (isset($_POST['description_apport_libre']) && $_POST['description_apport_libre'] != null)
                    $description .= ' ('.$_POST['description_apport_libre'].')';

                $finance = Finances::insertFinance(Pages::getByUuid($_GET['page'])->id(), 2, $description, $amount, date('Y-m-d H:i:s'));

                for ($i = 0; $i < sizeof($members); $i++)
                {
                    Contributions::insertContribution($finance->id(), $members[$i]);
                }

                $_SESSION['success_message'] = "L'apport a bien été ajouté.";
                header('Location:index.php?page='.$_GET['page'].'&ctrl=finances&action=afficher');
                exit;
            }
            else
            {
                $_SESSION['error_message'] = "Le montant doit être un chiffre ou un nombre à virgule.";
                header('Location:index.php?page='.$_GET['page'].'&ctrl=finances&action=afficher');
                exit;
            }
        }
        else
        {
            $_SESSION['error_message'] = "Veuillez renseigner un montant ainsi que les personnes ayant apporté de l'argent.";
            header('Location:index.php?page='.$_GET['page'].'&ctrl=finances&action=afficher');
            exit;
        }
    }

    public function get_supprimer()
    {
        $finance = Finances::getById($_GET['id']);
        $finance->deleteFinance();
        $finance->save();

        $msg = '';

        //On vérifie s'il s'agit d'une vente de merch
        $solds = Sold::getAllByFinance($finance->id());
        if ($solds != null)
        {
            foreach ($solds as $s)
            {
                $sold = Sold::getById($s->id());
                //On remet en stock
                if ($sold->is_garment() == 0)
                {
                    $inventory = Inventories::getById($s->id_inventory());
                    $inventory->modifyInventory($inventory->description(), $inventory->price(), $inventory->stock() + $s->number_sold());
                    $inventory->save();
                }
                else
                {
                    $productSize = ProductSizes::getById($s->id_inventory());
                    $productSize->modifyProductSize($productSize->stock() + $s->number_sold());
                    $productSize->save();
                }

                $sold->deleteSold();
            }

            $msg = " et les produits remis en stock";
        }

        $type = ($finance->type() == 1) ? "L'entrée" : "La sortie";

        $_SESSION['success_message'] = $type." d'argent a bien été supprimée$msg.";
        header('Location:index.php?page='.$_GET['page'].'&ctrl=finances&action=afficher');
        exit;
    }

    public function get_export()
    {
        $page = Pages::getByUuid($_GET['page']);
        $finances = Finances::getAllByPage($page->id());
        $amounts = Finances::getCaisseByPage($page->id());
        $recettes_totales = $amounts[0] / 100;
        $caisse = $amounts[1] / 100;
        $apport = $amounts[2] / 100;
        $reel_gagne = $amounts[3] / 100;

        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => array('argb' => '0000000'),
                ),
            ),
        );

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Finances de ".$page->name());

        foreach(range('A', 'B') as $col) :
            $spreadsheet->getActiveSheet()->getStyle($col.'6')->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle($col.'6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e8e8e8');
            $spreadsheet->getActiveSheet()->getStyle($col.'6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
            $spreadsheet->getActiveSheet()->getStyle($col.'6')->getAlignment()->setHorizontal('center');
            $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        endforeach;

        foreach(range('C', 'D') as $col) :
            $spreadsheet->getActiveSheet()->getStyle($col.'6')->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle($col.'6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ec7c77');
            $spreadsheet->getActiveSheet()->getStyle($col.'6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
            $spreadsheet->getActiveSheet()->getStyle($col.'6')->getAlignment()->setHorizontal('center');
            $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        endforeach;

        $spreadsheet->getActiveSheet()->getStyle('E6')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('E6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('a1ccff');
        $spreadsheet->getActiveSheet()->getStyle('E6')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
        $spreadsheet->getActiveSheet()->getStyle('E6')->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

        $sheet->setCellValue('A6', 'Date');
        $sheet->setCellValue('B6', 'Description');
        $sheet->setCellValue('C6', 'Entrée (en €)');
        $sheet->setCellValue('D6', 'Sortie (en €)');
        $sheet->setCellValue('E6', 'Solde (en €)');

        $lignes = array();
        $total = 0;
        foreach ($finances as $f)
        {
            $date_ajout = ($f->date_add() != '') ? substr($f->date_add(), 8, 2)."/".substr($f->date_add(), 5, 2)."/".substr($f->date_add(), 0, 4) : '';
            $entree = '';
            $sortie = '';
            if ($f->type() == 1 || $f->type() == 2)
                $entree = str_replace(',', '.', ($f->amount() / 100));
            if ($f->type() == 0 || $f->type() == 2)
                $sortie = str_replace(',', '.', ($f->amount() / 100));

            if ($f->type() == 1)
                $total = $total + ($f->amount() / 100);
            else if ($f->type() == 2)
                $total = $total;
            else
                $total = $total - ($f->amount() / 100);

            $total = str_replace(',', '.', $total);

            $lignes[] = array($date_ajout, $f->description(), $entree, $sortie, $total, $f->type());   
        }

        $spreadsheet->getActiveSheet()->mergeCells('A1:B1');

        $sheet->setCellValue('A1', $page->name().' - Trésorerie');
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');

        $sheet->setCellValue('C1', 'Recettes');
        $sheet->setCellValue('D1', strval($recettes_totales));
        $sheet->setCellValue('C2', 'Caisse');
        $sheet->setCellValue('D2', strval($caisse));
        $sheet->setCellValue('C3', 'Investi');
        $sheet->setCellValue('D3', strval($apport));
        $sheet->setCellValue('C4', 'Réel gagné');
        $sheet->setCellValue('D4', strval($reel_gagne));

        if (stripos($caisse, '-') !== false)
            $color = 'ec7c77';
        else
            $color = '4caf42';

        $spreadsheet->getActiveSheet()->getStyle('D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB($color);

        for ($i = 1; $i <= 4; $i++) :
            $spreadsheet->getActiveSheet()->getStyle('C'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('D'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('C'.$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('e8e8e8');
            $spreadsheet->getActiveSheet()->getStyle('C'.$i)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
            $spreadsheet->getActiveSheet()->getStyle('C'.$i)->getAlignment()->setHorizontal('center');
        endfor;

        $i = 7;

        foreach ($lignes as $ligne)
        {
            $sheet->setCellValue('A'.$i, $ligne[0]);
            $sheet->setCellValue('B'.$i, $ligne[1]);
            $sheet->setCellValue('C'.$i, $ligne[2]);
            $sheet->setCellValue('D'.$i, $ligne[3]);
            $sheet->setCellValue('E'.$i, $ligne[4]);

            $spreadsheet->getActiveSheet()->getStyle('A'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('B'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('C'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('D'.$i)->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getStyle('E'.$i)->applyFromArray($styleArray);

            if ($ligne[5] == 2)
                $spreadsheet->getActiveSheet()->getStyle('C'.$i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4caf42');

            $i = $i + 1;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="export_finances_'.date('Y-m-d H:i:s').'.xlsx"');
        header('Cache-Control: max-age=0');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
    }
}