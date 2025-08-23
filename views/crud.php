<?php
    $contacts = $data['contacts']['list'];
    $deals = $data['deals']['list'];
?>

<div class="section" id="contacts-section">
    <div class="toolbar">
      <h1><?= htmlspecialchars($data['contacts']['title'] ?? 'Контакты') ?></h1>
      <button class="btn primary" onclick="openContactModal()">Добавить контакт</button>
    </div>
    <table>
      <thead>
        <tr>
          <th style="width:80px;">ID</th>
          <th>ФИО</th>
          <th>Сделки</th>
          <th style="width:220px;">Действия</th>
        </tr>
      </thead>
      <tbody id="contacts-tbody">
      <?php foreach ($contacts as $c): ?>
        <tr data-id="<?= (int)$c['id'] ?>">
          <td><?= (int)$c['id'] ?></td>
          <td><?= htmlspecialchars($c['label']) ?></td>
          <td>
            <?php if (!empty($c['details']['Сделки'])): ?>
              <?php foreach ($c['details']['Сделки'] as $did => $dlabel): ?>
                <span class="pill"><?= htmlspecialchars($did . ': ' . $dlabel) ?></span>
              <?php endforeach; ?>
            <?php else: ?>
              <span class="muted">нет</span>
            <?php endif; ?>
          </td>
          <td class="actions">
            <button class="btn" onclick="openContactModal(<?= (int)$c['id'] ?>)">Редактировать</button>
            <button class="btn danger" onclick="deleteItem('contacts', <?= (int)$c['id'] ?>)">Удалить</button>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
</div>

<div class="section" id="deals-section">
    <div class="toolbar">
        <h1><?= htmlspecialchars($data['deals']['title'] ?? 'Сделки') ?></h1>
        <button class="btn primary" onclick="openDealModal()">Добавить сделку</button>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width:80px;">ID</th>
                <th>Наименование</th>
                <th>Сумма</th>
                <th>Контакты</th>
                <th style="width:220px;">Действия</th>
            </tr>
        </thead>
        <tbody id="deals-tbody">
        <?php foreach ($deals as $d): ?>
            <tr data-id="<?= (int)$d['id'] ?>">
            <td><?= (int)$d['id'] ?></td>
            <td><?= htmlspecialchars($d['label']) ?></td>
            <td><?= htmlspecialchars($d['details']['Сумма'] ?? 0) ?></td>
            <td>
                <?php if (!empty($d['details']['Контакты'])): ?>
                <?php foreach ($d['details']['Контакты'] as $cid => $clabel): ?>
                    <span class="pill"><?= htmlspecialchars($cid . ': ' . $clabel) ?></span>
                <?php endforeach; ?>
                <?php else: ?>
                <span class="muted">нет</span>
                <?php endif; ?>
            </td>
            <td class="actions">
                <button class="btn" onclick="openDealModal(<?= (int)$d['id'] ?>)">Редактировать</button>
                <button class="btn danger" onclick="deleteItem('deals', <?= (int)$d['id'] ?>)">Удалить</button>
            </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="modal-backdrop" id="contact-backdrop">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title" id="contact-modal-title">Новый контакт</div>
            <button class="btn" onclick="closeContactModal()">×</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="contact-id" />
            <div>
                <label>Имя</label>
                <input type="text" id="contact-first" placeholder="Иван" />
            </div>
            <div>
                <label>Фамилия</label>
                <input type="text" id="contact-last" placeholder="Петров" />
            </div>
            <div>
                <label>Сделки (множественный выбор)</label>
                <select id="contact-deals" multiple></select>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" onclick="closeContactModal()">Отмена</button>
            <button class="btn primary" onclick="saveContact()">Сохранить</button>
        </div>
    </div>
</div>

<div class="modal-backdrop" id="deal-backdrop">
    <div class="modal">
      <div class="modal-header">
        <div class="modal-title" id="deal-modal-title">Новая сделка</div>
        <button class="btn" onclick="closeDealModal()">×</button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="deal-id" />
        <div>
          <label>Наименование</label>
          <input type="text" id="deal-name" placeholder="Пока думают" />
        </div>
        <div>
          <label>Сумма</label>
          <input type="number" id="deal-amount" placeholder="0" />
        </div>
        <div>
          <label>Контакты (множественный выбор)</label>
          <select id="deal-contacts" multiple></select>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn" onclick="closeDealModal()">Отмена</button>
        <button class="btn primary" onclick="saveDeal()">Сохранить</button>
      </div>
    </div>
</div>


<script>
    const DATA = <?php echo json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); ?>;
</script>