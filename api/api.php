<?php
header('Content-Type: application/json; charset=UTF-8');

define("ROOT", '/Applications/MAMP/htdocs/zadanie'); // косяяк хз как решить

$file = ROOT . '/data/content.json';

if (!file_exists($file)) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'content.json not found']);
    exit;
}

$data = json_decode(file_get_contents($file), true) ?: ['contacts'=>['list'=>[]],'deals'=>['list'=>[]]];

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? null;
$type   = $input['type']   ?? null;

function nextId(array $list): int {
    if (empty($list)) return 1; // елси нет то просто айдишку первую возвращаю
    $keys = array_keys($list);
    $keys = array_map('intval', $keys);
    return max($keys) + 1; // находим самый большой id и прото увеличиваю
}

function saveData($file, $data){
    file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
}
// ['contacts','deals'] потом в отедльный массив закинуть
if (!in_array($type, ['contacts','deals'], true)) {
    echo json_encode(['status'=>'error','message'=>'bad type']);
    exit;
}

if ($action === 'create' || $action === 'update') {
    $item = $input['item'] ?? [];
    if ($type === 'contacts') {
        $id = isset($item['id']) && $item['id'] ? (int)$item['id'] : nextId($data['contacts']['list']);
        $first = $item['details']['Имя'] ?? '';
        $last  = $item['details']['Фамилия'] ?? '';
        $label = trim($first . ' ' . $last);
        $selectedDeals = $item['deals'] ?? [];

        $prevDealsIds = [];
        if (isset($data['contacts']['list'][$id]['details']['Сделки'])) {
            $prevDealsIds = array_map('intval', array_keys($data['contacts']['list'][$id]['details']['Сделки']));
        }

        $data['contacts']['list'][$id] = [
            'id' => $id,
            'label' => $label,
            'details' => [
                'id контакта' => $id,
                'Имя' => $first,
                'Фамилия' => $last,
                'Сделки' => []
            ]
        ];

        foreach ($selectedDeals as $did) { // заполняем крч сделки
            if (isset($data['deals']['list'][$did])) {
                $data['contacts']['list'][$id]['details']['Сделки'][(string) $did] = $data['deals']['list'][$did]['label'];
            }
        }

        foreach ($selectedDeals as $did) {
            if (!isset($data['deals']['list'][$did])) continue;
            if (!isset($data['deals']['list'][$did]['details']['Контакты'])) {
                $data['deals']['list'][$did]['details']['Контакты'] = [];
            }
            $data['deals']['list'][$did]['details']['Контакты'][(string) $id] = $label;
        }
        
        $toRemove = array_diff($prevDealsIds, array_map('intval',$selectedDeals));

        foreach ($toRemove as $did) {
            if (isset($data['deals']['list'][$did]['details']['Контакты'][(string) $id])) {
                unset($data['deals']['list'][$did]['details']['Контакты'][(string) $id]);
            }
        }

        saveData($file, $data);
        echo json_encode(['status'=>'ok','id'=>$id]);
        exit;
    } elseif ($type === 'deals') {
        $id = isset($item['id']) && $item['id'] ? (int) $item['id'] : nextId($data['deals']['list']);
        $name   = $item['details']['Наименование'] ?? '';
        $amount = isset($item['details']['Сумма']) ? (int) $item['details']['Сумма'] : 0;
        $label  = $name;
        $selectedContacts = $item['contacts'] ?? [];

        $prevContactIds = [];
        if (isset($data['deals']['list'][$id]['details']['Контакты'])) {
            $prevContactIds = array_map('intval', array_keys($data['deals']['list'][$id]['details']['Контакты']));
        }

        // сама сделка
        $data['deals']['list'][$id] = [
            'id' => $id,
            'label' => $label,
            'details' => [
                'id сделки' => $id,
                'Наименование' => $name,
                'Сумма' => $amount,
                'Контакты' => []
            ]
        ];

        foreach ($selectedContacts as $cid) {
            if (isset($data['contacts']['list'][$cid])) {
                $data['deals']['list'][$id]['details']['Контакты'][(string)$cid] = $data['contacts']['list'][$cid]['label'];
            }
        }

        foreach ($selectedContacts as $cid) {
            if (!isset($data['contacts']['list'][$cid])) continue; // если есть то продолжаем

            if (!isset($data['contacts']['list'][$cid]['details']['Сделки'])) {
                $data['contacts']['list'][$cid]['details']['Сделки'] = [];
            }
            $data['contacts']['list'][$cid]['details']['Сделки'][(string)$id] = $label;
        }
        
        $toRemove = array_diff($prevContactIds, array_map('intval',$selectedContacts)); // в числа всё преобразовываю и сравниваем массивы
        foreach ($toRemove as $cid) { 
            if (isset($data['contacts']['list'][$cid]['details']['Сделки'][(string)$id])) {
                unset($data['contacts']['list'][$cid]['details']['Сделки'][(string)$id]);
            }
        }

        saveData($file, $data);
        echo json_encode(['status'=>'ok','id'=>$id]);
        exit;
    }
}

if ($action === 'delete') {
    $id = (int) ($input['id'] ?? 0);
    if ($type === 'contacts') {
        // удаляем контакт + чистим ссылки в сделках
        if (isset($data['contacts']['list'][$id])) {
            foreach ($data['deals']['list'] as &$deal) {
                if (isset($deal['details']['Контакты'][(string)$id])) {
                    unset($deal['details']['Контакты'][(string)$id]);
                }
            }
            unset($deal);
            unset($data['contacts']['list'][$id]);
        }
    } elseif ($type === 'deals') {
        if (isset($data['deals']['list'][$id])) {
            foreach ($data['contacts']['list'] as &$contact) {
                if (isset($contact['details']['Сделки'][(string)$id])) {
                    unset($contact['details']['Сделки'][(string)$id]);
                }
            }
            unset($contact);
            unset($data['deals']['list'][$id]);
        }
    }
    saveData($file, $data);
    echo json_encode(['status'=>'ok']);
    exit;
}

echo json_encode(['status'=>'error','message'=>'bad request']);