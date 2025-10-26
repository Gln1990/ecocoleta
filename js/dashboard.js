// js/dashboard.js
class Dashboard {
    constructor() {
        this.init();
        this.loadStatistics();
        this.setupEventListeners();
    }

    init() {
        console.log('Dashboard EcoColeta inicializado');
        this.updateGreeting();
        this.checkNotifications();
    }

    updateGreeting() {
        const hour = new Date().getHours();
        let greeting = 'Boa noite';
        
        if (hour >= 5 && hour < 12) {
            greeting = 'Bom dia';
        } else if (hour >= 12 && hour < 18) {
            greeting = 'Boa tarde';
        }
        
        const greetingElement = document.querySelector('.dashboard-header h1');
        if (greetingElement) {
            const userName = greetingElement.textContent.split(',')[1]?.trim() || '';
            greetingElement.innerHTML = `${greeting}, <span class="user-name">${userName}</span>!`;
        }
    }

    async loadStatistics() {
        try {
            // Simular carregamento de estatísticas
            await this.simulateLoading();
            
            // Animar contadores
            this.animateCounters();
            
        } catch (error) {
            console.error('Erro ao carregar estatísticas:', error);
        }
    }

    animateCounters() {
        const counters = document.querySelectorAll('.stat-number');
        
        counters.forEach(counter => {
            const target = parseInt(counter.textContent);
            const duration = 2000; // 2 segundos
            const step = target / (duration / 16); // 60fps
            let current = 0;
            
            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                counter.textContent = Math.floor(current);
            }, 16);
        });
    }

    setupEventListeners() {
        // Filtros de status
        const statusFilters = document.querySelectorAll('.status-filter');
        statusFilters.forEach(filter => {
            filter.addEventListener('click', (e) => {
                e.preventDefault();
                this.filterColetas(e.target.dataset.status);
            });
        });

        // Busca em tempo real
        const searchInput = document.querySelector('#search-coletas');
        if (searchInput) {
            searchInput.addEventListener('input', this.debounce(() => {
                this.searchColetas(searchInput.value);
            }, 300));
        }

        // Botões de ação
        this.setupActionButtons();
        
        // Notificações
        this.setupNotifications();
    }

    filterColetas(status) {
        const coletas = document.querySelectorAll('.coleta-card');
        
        coletas.forEach(coleta => {
            const coletaStatus = coleta.querySelector('.status-badge').textContent.toLowerCase();
            
            if (status === 'all' || coletaStatus === status) {
                coleta.style.display = 'block';
                setTimeout(() => {
                    coleta.style.opacity = '1';
                    coleta.style.transform = 'translateY(0)';
                }, 100);
            } else {
                coleta.style.opacity = '0';
                coleta.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    coleta.style.display = 'none';
                }, 300);
            }
        });

        // Atualizar filtro ativo
        document.querySelectorAll('.status-filter').forEach(filter => {
            filter.classList.remove('active');
        });
        event.target.classList.add('active');
    }

    searchColetas(term) {
        const coletas = document.querySelectorAll('.coleta-card');
        const termLower = term.toLowerCase();
        
        coletas.forEach(coleta => {
            const text = coleta.textContent.toLowerCase();
            if (text.includes(termLower)) {
                coleta.style.display = 'block';
                setTimeout(() => {
                    coleta.style.opacity = '1';
                }, 100);
            } else {
                coleta.style.opacity = '0';
                setTimeout(() => {
                    coleta.style.display = 'none';
                }, 300);
            }
        });
    }

    setupActionButtons() {
        // Botão de confirmar coleta
        const confirmButtons = document.querySelectorAll('.btn-confirm');
        confirmButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.confirmColeta(button.dataset.id);
            });
        });

        // Botão de cancelar coleta
        const cancelButtons = document.querySelectorAll('.btn-cancel');
        cancelButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.cancelColeta(button.dataset.id);
            });
        });
    }

    async confirmColeta(coletaId) {
        if (!confirm('Deseja confirmar que esta coleta foi realizada?')) {
            return;
        }

        try {
            // Simular requisição AJAX
            await this.simulateApiCall();
            
            this.showNotification('Coleta confirmada com sucesso!', 'success');
            this.updateColetaStatus(coletaId, 'realizada');
            
        } catch (error) {
            this.showNotification('Erro ao confirmar coleta.', 'error');
        }
    }

    async cancelColeta(coletaId) {
        if (!confirm('Deseja cancelar esta coleta?')) {
            return;
        }

        try {
            // Simular requisição AJAX
            await this.simulateApiCall();
            
            this.showNotification('Coleta cancelada.', 'info');
            this.updateColetaStatus(coletaId, 'cancelada');
            
        } catch (error) {
            this.showNotification('Erro ao cancelar coleta.', 'error');
        }
    }

    updateColetaStatus(coletaId, status) {
        const coleta = document.querySelector(`[data-coleta-id="${coletaId}"]`);
        if (coleta) {
            const statusBadge = coleta.querySelector('.status-badge');
            statusBadge.className = `status-badge status-${status}`;
            statusBadge.textContent = this.formatStatus(status);
            
            // Atualizar ações disponíveis
            this.updateColetaActions(coleta, status);
        }
    }

    updateColetaActions(coleta, status) {
        const actions = coleta.querySelector('.coleta-actions');
        if (!actions) return;

        let newActions = '';
        
        switch(status) {
            case 'pendente':
                newActions = `
                    <button class="btn btn-sm btn-primary btn-confirm" data-id="${coleta.dataset.coletaId}">
                        Confirmar
                    </button>
                    <button class="btn btn-sm btn-danger btn-cancel" data-id="${coleta.dataset.coletaId}">
                        Cancelar
                    </button>
                `;
                break;
            case 'agendada':
                newActions = `
                    <button class="btn btn-sm btn-success btn-confirm" data-id="${coleta.dataset.coletaId}">
                        Realizada
                    </button>
                    <button class="btn btn-sm btn-danger btn-cancel" data-id="${coleta.dataset.coletaId}">
                        Cancelar
                    </button>
                `;
                break;
            case 'realizada':
                newActions = '<span class="text-success">✅ Concluída</span>';
                break;
            case 'cancelada':
                newActions = '<span class="text-danger">❌ Cancelada</span>';
                break;
        }
        
        actions.innerHTML = newActions;
        this.setupActionButtons(); // Reconfigurar event listeners
    }

    setupNotifications() {
        // Verificar se há novas notificações
        this.checkNewNotifications();
        
        // Sistema de notificações em tempo real (simulado)
        setInterval(() => {
            this.simulateNewNotification();
        }, 30000); // A cada 30 segundos
    }

    async checkNewNotifications() {
        try {
            // Simular verificação de notificações
            const hasNotifications = Math.random() > 0.7;
            
            if (hasNotifications) {
                this.showNotification('Você tem novas coletas pendentes!', 'info');
            }
        } catch (error) {
            console.error('Erro ao verificar notificações:', error);
        }
    }

    simulateNewNotification() {
        // Simular nova notificação ocasionalmente
        if (Math.random() > 0.8) {
            const messages = [
                'Nova coleta solicitada na sua região!',
                'Lembre-se: coleta programada para amanhã!',
                'Atualização nas rotas de coleta disponível!'
            ];
            const randomMessage = messages[Math.floor(Math.random() * messages.length)];
            this.showNotification(randomMessage, 'info');
        }
    }

    showNotification(message, type = 'info') {
        // Criar elemento de notificação
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button class="notification-close">&times;</button>
            </div>
        `;

        // Adicionar ao body
        document.body.appendChild(notification);

        // Animar entrada
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);

        // Configurar auto-remover
        setTimeout(() => {
            this.hideNotification(notification);
        }, 5000);

        // Configurar botão fechar
        notification.querySelector('.notification-close').addEventListener('click', () => {
            this.hideNotification(notification);
        });
    }

    hideNotification(notification) {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }

    formatStatus(status) {
        const statusMap = {
            'pendente': 'Pendente',
            'agendada': 'Agendada',
            'realizada': 'Realizada',
            'cancelada': 'Cancelada'
        };
        return statusMap[status] || status;
    }

    // Utilitários
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    async simulateLoading() {
        return new Promise(resolve => {
            setTimeout(resolve, 1000);
        });
    }

    async simulateApiCall() {
        return new Promise(resolve => {
            setTimeout(resolve, 500);
        });
    }
}

// CSS para notificações (adicionar ao style.css)
const notificationStyles = `
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    padding: 1rem;
    min-width: 300px;
    transform: translateX(400px);
    transition: transform 0.3s ease;
    z-index: 10000;
    border-left: 4px solid #3498db;
}

.notification.show {
    transform: translateX(0);
}

.notification-success {
    border-left-color: #27ae60;
}

.notification-error {
    border-left-color: #e74c3c;
}

.notification-info {
    border-left-color: #3498db;
}

.notification-warning {
    border-left-color: #f39c12;
}

.notification-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notification-message {
    flex: 1;
    margin-right: 1rem;
}

.notification-close {
    background: none;
    border: none;
    font-size: 1.2rem;
    cursor: pointer;
    color: #7f8c8d;
}

.notification-close:hover {
    color: #34495e;
}

.user-name {
    color: #f1c40f;
    font-weight: bold;
}

.status-filter.active {
    background: var(--primary-color);
    color: white;
}
`;

// Adicionar estilos das notificações
const styleSheet = document.createElement('style');
styleSheet.textContent = notificationStyles;
document.head.appendChild(styleSheet);

// Inicializar dashboard quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    window.dashboard = new Dashboard();
});

// Exportar para uso global
if (typeof module !== 'undefined' && module.exports) {
    module.exports = Dashboard;
}