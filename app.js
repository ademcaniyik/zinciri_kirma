document.addEventListener('DOMContentLoaded', () => {
    loadHabits();
    document.getElementById('habitForm').addEventListener('submit', addHabit);
});

async function addHabit(e) {
    e.preventDefault();
    const habitName = document.getElementById('habitName').value;
    const targetDays = parseInt(document.getElementById('targetDays').value);
    
    if (!habitName || !targetDays) return;

    try {
        const response = await fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                name: habitName,
                targetDays: targetDays
            })
        });

        if (response.ok) {
            document.getElementById('habitForm').reset();
            await loadHabits();
        } else {
            console.error('Alışkanlık eklenirken hata oluştu');
        }
    } catch (error) {
        console.error('Hata:', error);
    }
}

async function loadHabits() {
    try {
        const response = await fetch('api.php');
        const habits = await response.json();
        displayHabits(habits);
    } catch (error) {
        console.error('Hata:', error);
    }
}

function displayHabits(habits) {
    const habitList = document.getElementById('habitList');
    
    habitList.innerHTML = habits.map(habit => {
        const chainCircles = generateChainCircles(habit);
        return `
            <div class="habit-card" id="habit-${habit.id}">
                <div class="habit-header">
                    <span class="habit-title">${habit.name} (${habit.target_days} gün)</span>
                    <span class="delete-habit" onclick="deleteHabit(${habit.id})">🗑️</span>
                </div>
                <div class="chain-container">
                    ${chainCircles}
                </div>
            </div>
        `;
    }).join('');
}

function generateChainCircles(habit) {
    let circles = '';
    
    for (let i = 0; i < habit.target_days; i++) {
        const date = new Date(habit.created_at);
        date.setDate(date.getDate() + i);
        const dateStr = date.toISOString().split('T')[0];
        const isCompleted = habit.chain.includes(dateStr);
        
        circles += `
            <div class="chain-circle ${isCompleted ? 'completed' : ''}" 
                 onclick="toggleDay(${habit.id}, '${dateStr}')"
                 title="${dateStr}">
                 ${i + 1}
            </div>
        `;
    }
    
    return circles;
}

async function toggleDay(habitId, date) {
    try {
        const response = await fetch('api.php?action=toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                habitId: parseInt(habitId),
                date: date
            })
        });

        if (!response.ok) {
            const errorData = await response.json();
            console.error('Sunucu hatası:', errorData.error);
            return;
        }

        await loadHabits();
    } catch (error) {
        console.error('Hata:', error);
    }
}

async function deleteHabit(habitId) {
    if (!confirm('Bu alışkanlığı silmek istediğinizden emin misiniz?')) return;
    
    try {
        const response = await fetch(`api.php?id=${habitId}`, {
            method: 'DELETE'
        });

        if (response.ok) {
            await loadHabits();
        } else {
            console.error('Alışkanlık silinirken hata oluştu');
        }
    } catch (error) {
        console.error('Hata:', error);
    }
}
