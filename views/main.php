<div class="container">
    <div class="block">
        <h2>Меню</h2>
        <?php foreach($data as $key => $menu): ?>
            <?php $flagtitle = $url[0] == $key; ?>
            <div class="item <?= $flagtitle ? 'active' : '' ?>">
                <a href="/<?= $key ?>/<?= array_key_first($menu['list'])?>/"><?= $menu['title'] ?></a>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="block">
        <h2>Список</h2>
        <?php foreach($data[$url[0]]['list'] as $key => $menuList): ?>
            <?php $flag = $url[1] == $key; ?>
            <div class="item <?= $flag ? 'active' : '' ?>">
                <a href="/<?= $url[0] ?>/<?= $key ?>/"><?= $menuList['label'] ?></a>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="block">
        <h2>Содержание</h2>
        <div class="content">
            <?php $content = $data[$url[0]]['list'][$url[1]]; ?>
            <h3 style="margin:10px 0;"><?= $content['label'] ?></h3>
            <table>
                <?php foreach ($content['details'] as $keydetail => $valuedetail): ?>
                <tr>
                    <th><?= $keydetail ?>:</th>
                    <?php if(!is_array($valuedetail)): ?>
                    <td><?= $valuedetail ?></td>
                    <?php else: ?>
                        <?php if($content['details'][array_key_last($content['details'])]): ?>
                            <td>
                                <table>
                                <?php foreach($content['details'][array_key_last($content['details'])] as $keydeal => $contentdeal):?>
                                    <tr>
                                        <th>
                                            <?= $keydeal ?>
                                        </th>
                                        <td>
                                            <?= $contentdeal ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </table>
                            </td>
                        <?php else: ?>
                            <td>Отсутствует</td>
                        <?php endif; ?>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>