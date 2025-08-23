const topic = window.location.pathname.split("/").filter(Boolean);
const subtopicItems = document.querySelectorAll(".subtopic-items");

const activeTopic = topic.length > 0 ? topic[0] : "topic-1";

subtopicItems.forEach(subtopic => {
    if (subtopic.getAttribute("data-url") !== `/${activeTopic}`) {
        subtopic.classList.add("hidden");
    } else {
        subtopic.classList.remove("hidden");
    }
});

function $(sel){ 
    return document.querySelector(sel); 
}
    
function $all(sel){ 
    return Array.from(document.querySelectorAll(sel)); 
}

function openContactModal(id = null) {
    const dealsSelect = $('#contact-deals');
    dealsSelect.innerHTML = '';
    
    const deals = DATA.deals?.list || {};

    Object.values(deals).forEach(d => {
        const opt = document.createElement('option');
        opt.value = d.id;
        opt.textContent = `${d.id}: ${d.label}`; // айди и фи
        dealsSelect.appendChild(opt);
    });

    if (id) {
        const item = DATA.contacts.list[id];
        $('#contact-modal-title').textContent = 'Редактировать контакт';
        $('#contact-id').value = item.id;
        $('#contact-first').value = item.details['Имя'] || '';
        $('#contact-last').value  = item.details['Фамилия'] || '';

        const selected = item.details['Сделки'] ? Object.keys(item.details['Сделки']).map(Number) : [];

        $all('#contact-deals option').forEach(o => { 
            if (selected.includes(Number(o.value))) o.selected = true; 
        });

      } else {
        $('#contact-modal-title').textContent = 'Новый контакт';
        $('#contact-id').value = '';
        $('#contact-first').value = '';
        $('#contact-last').value  = '';
      }

    $('#contact-backdrop').style.display = 'flex';
}

function closeContactModal() { 
    $('#contact-backdrop').style.display = 'none'; 
}

function openDealModal(id = null) {
    const contactsSelect = $('#deal-contacts');
    contactsSelect.innerHTML = '';

    const contacts = DATA.contacts?.list || {}; 
    // прохолимся по контактам и в селект кидаем
    Object.values(contacts).forEach(c => {
        const opt = document.createElement('option');
        opt.value = c.id; // бл че 
        opt.textContent = `${c.id}: ${c.label}`;
        contactsSelect.appendChild(opt);
    });

    if (id) {
        const item = DATA.deals.list[id]; // получаем айдишку от сделок
        $('#deal-modal-title').textContent = 'Редактировать сделку';
        $('#deal-id').value   = item.id;
        $('#deal-name').value = item.details['Наименование'] || item.label || '';
        $('#deal-amount').value = item.details['Сумма'] ?? 0;
        const selected = item.details['Контакты'] ? Object.keys(item.details['Контакты']).map(Number) : [];
        $all('#deal-contacts option').forEach(o => { if (selected.includes(Number(o.value))) o.selected = true; });
    } else {
        $('#deal-modal-title').textContent = 'Новая сделка';
        $('#deal-id').value = '';
        $('#deal-name').value = '';
        $('#deal-amount').value = 0;
    }

      $('#deal-backdrop').style.display = 'flex';
}

function closeDealModal(){ 
    $('#deal-backdrop').style.display = 'none'; 
}

async function saveContact(){ // это для сохранения конактов
    const id = $('#contact-id').value.trim();
    const first = $('#contact-first').value.trim();
    const last  = $('#contact-last').value.trim();
    const selectedDeals = Array.from($('#contact-deals').selectedOptions).map(o => Number(o.value));

    const payload = {
        action: id ? 'update' : 'create',
        type: 'contacts',
        item: {
            id: id ? Number(id) : null,
            details: {
            'Имя': first,
            'Фамилия': last
            },
            deals: selectedDeals
        }
    };

    const res = await fetch('/api/api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(payload)
      });

    const json = await res.json();

    if (json.status === 'ok') {
        location.reload();
    }

}

async function saveDeal(){
    const id = $('#deal-id').value.trim();
    const name = $('#deal-name').value.trim();
    const amount = Number($('#deal-amount').value || 0);
    const selectedContacts = Array.from(
        $('#deal-contacts').selectedOptions).map(o => Number(o.value) // то что выбрал кидаю
    );

    const payload = {
        action: id ? 'update' : 'create',
        type: 'deals',
        item: {
        id: id ? Number(id) : null,
        details: {
            'Наименование': name,
            'Сумма': amount
          },
          contacts: selectedContacts
        }
      };

    const res = await fetch('/api/api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(payload)
    });

    const json = await res.json();

    if (json.status === 'ok') {
        location.reload();
    }
}

async function deleteItem(type, id){
    if (!confirm(`Удалить ${type === 'contacts' ? 'контакт' : 'сделку'} #${id}?`)) return;

    const res = await fetch('/api/api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ action:'delete', type, id })
    });

    const json = await res.json();

    if (json.status === 'ok') {
    location.reload();
    }
}