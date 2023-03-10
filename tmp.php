<table class="table table-inventory">
            <thead class="table-header">
                <tr>
                    <th scope="col">Image</th>
                    <th scope="col">Description</th>
                    <th scope="col" class="text-center">Prix (en €)</th>
                    <th scope="col" class="text-center">Stock</th>
                    <th scope="col" class="text-center">Valeur (en €)</th>
                    <?php if ($rank->inventory() == 1) : ?>
                        <th scope="col"></th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                    $total_stock = 0;
                    $total = 0;
                    $inventory_id = 0;
                ?>
                <?php if (!empty($products)) : ?>
                    <?php for ($i = 0; $i < sizeof($products); $i++) : ?>
                        <?php if ($products[$i]['id'] != $inventory_id) : ?>
                            <?php $inventory_id = $products[$i]['id']; ?>
                            <tr>
                                <td>
                                    <img src="<?= Inventories::getById($inventory_id)->picture(); ?>">
                                </td>
                                <td><?= Inventories::getById($inventory_id)->description(); ?></td>
                                <td class="text-center"><?= (Inventories::getById($inventory_id)->price() / 100); ?></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <?php if ($rank->inventory() == 1) : ?>
                                    <td class="text-end">
                                        <a href="#" class="modify" data-page="<?= $page->uuid(); ?>" data-id="<?= Inventories::getById($inventory_id)->id(); ?>" data-bs-toggle="modal" data-bs-target="#modal_modifier_produit"><span class="material-icons">edit_note</span></a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endif; ?>
                        <?php if ($products[$i]['id_product_size'] != 0) : ?>
                            <tr>
                                <td>
                                </td>
                                <td><?= $products[$i]['description']; ?></td>
                                <td class="text-center"></td>
                                <td class="text-center"><span class="badge <?php if ($products[$i]['stock'] >= 15) : ?>bg-success<?php elseif ($products[$i]['stock'] > 10 && $products[$i]['stock'] < 15) : ?>bg-warning<?php else : ?>bg-danger<?php endif; ?>"><?= $products[$i]['stock']; ?></span></td>
                                <td class="text-center"><?= ($products[$i]['price'] * $products[$i]['stock']) / 100; ?></td>
                                <?php if ($rank->inventory() == 1) : ?>
                                    <td>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endif; ?>
                        <?php
                            $total_stock += $products[$i]['stock'];
                            $total += ($products[$i]['price'] * $products[$i]['stock']) / 100;
                        ?>
                    <?php endfor; ?>
                    <tr class="table-primary">
                        <td colspan="3"></td>
                        <td class="text-center"><?= $total_stock; ?></td>
                        <td class="text-center"><?= $total; ?></td>
                        <?php if ($rank->inventory() == 1) : ?>
                            <td></td>
                        <?php endif; ?>
                    </tr>
                <?php else : ?>
                    <tr>
                        <td colspan="<?php if ($rank->inventory() == 1) : ?>6<?php else : ?>5<?php endif; ?>">
                            <div class="alert alert-info">
                                <span class="material-icons">info_outline</span>
                                Aucun produit n'a encore été ajouté dans l'inventaire.
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>