<?php 
    // echo "<pre>";
    // print_r($data);
    // print_r($params['subtopic']);
    // echo "</pre>";
?>

<div class="container">
    <div class="block">
        <h2>Тема</h2>
        <?php foreach($data as $topic): ?>
            <div class="item <?= $topic['url'] == '/'.$params['topic'] ? 'active' : '' ?>">
                <a href="<?= $topic['subtopics'][0]['url'] ?>"><?= $topic['title'] ?></a>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="block">
        <h2>Подтема</h2>
        <?php foreach($data as $topic): ?>
            <?php foreach($topic['subtopics'] as $subtopic): ?>
                <div class="item <?= $subtopic['url'] == '/'.$params['topic'].'/'.$params['subtopic'] ? 'active' : '' ?> subtopic-items" data-url="<?= $topic['url'] ?>">
                    <a href="<?= $subtopic['url'] ?>"><?= $subtopic['title'] ?></a>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>

    <div class="block">
        <h2>Содержание</h2>
        <div class="content">
           <?=$subtopiccontent?>
        </div>
    </div>
</div>