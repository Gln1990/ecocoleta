// js/mapa.js
class MapaRotas {
    constructor() {
        this.mapa = null;
        this.marcadores = [];
        this.rotasCamada = null;
        this.localizacaoUsuario = null;
        this.inicializarMapa();
        this.configurarEventos();
    }

    inicializarMapa() {
        // Coordenadas padrão (centro da cidade)
        const centroPadrao = [-23.5505, -46.6333]; // São Paulo
        
        this.mapa = L.map('mapa').setView(centroPadrao, 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 18
        }).addTo(this.mapa);

        // Tentar obter localização do usuário
        this.obterLocalizacaoUsuario();
        
        // Carregar rotas iniciais
        this.carregarRotas();
    }

    obterLocalizacaoUsuario() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.localizacaoUsuario = [
                        position.coords.latitude,
                        position.coords.longitude
                    ];
                    
                    this.adicionarMarcadorUsuario();
                    this.mapa.setView(this.localizacaoUsuario, 15);
                },
                (error) => {
                    console.log('Erro ao obter localização:', error);
                }
            );
        }
    }

    adicionarMarcadorUsuario() {
        if (this.localizacaoUsuario) {
            const marcadorUsuario = L.marker(this.localizacaoUsuario)
                .addTo(this.mapa)
                .bindPopup('<b>Sua Localização</b><br>Você está aqui!')
                .openPopup();
            
            marcadorUsuario.setIcon(
                L.icon({
                    iconUrl: 'data:image/svg+xml;base64,' + btoa(`
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#e74c3c" width="24px" height="24px">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                    `),
                    iconSize: [24, 24],
                    iconAnchor: [12, 24],
                    popupAnchor: [0, -24]
                })
            );
            
            this.marcadores.push(marcadorUsuario);
        }
    }

    async carregarRotas(bairroFiltro = '') {
        try {
            // Simulação de dados de rotas - na implementação real, viria do banco
            const rotas = await this.obterDadosRotas(bairroFiltro);
            this.desenharRotas(rotas);
        } catch (error) {
            console.error('Erro ao carregar rotas:', error);
        }
    }

    async obterDadosRotas(bairro) {
        // Em uma implementação real, isso viria de uma API
        // Aqui estamos simulando dados baseados nos bairros das rotas
        
        const rotasSimuladas = {
            'Centro': {
                coordenadas: [
                    [-23.5505, -46.6333],
                    [-23.5515, -46.6343],
                    [-23.5525, -46.6353],
                    [-23.5535, -46.6363]
                ],
                cor: '#2ecc71'
            },
            'Vila Madalena': {
                coordenadas: [
                    [-23.5470, -46.6910],
                    [-23.5480, -46.6920],
                    [-23.5490, -46.6930],
                    [-23.5500, -46.6940]
                ],
                cor: '#3498db'
            },
            'Pinheiros': {
                coordenadas: [
                    [-23.5670, -46.6920],
                    [-23.5680, -46.6930],
                    [-23.5690, -46.6940],
                    [-23.5700, -46.6950]
                ],
                cor: '#9b59b6'
            }
        };

        if (bairro && rotasSimuladas[bairro]) {
            return { [bairro]: rotasSimuladas[bairro] };
        }

        return bairro ? {} : rotasSimuladas;
    }

    desenharRotas(rotas) {
        // Limpar rotas anteriores
        if (this.rotasCamada) {
            this.mapa.removeLayer(this.rotasCamada);
        }

        this.rotasCamada = L.layerGroup().addTo(this.mapa);

        Object.entries(rotas).forEach(([bairro, dados]) => {
            // Desenhar linha da rota
            const polilinha = L.polyline(dados.coordenadas, {
                color: dados.cor,
                weight: 6,
                opacity: 0.7
            }).addTo(this.rotasCamada);

            // Adicionar marcadores nos pontos da rota
            dados.coordenadas.forEach((coordenada, index) => {
                L.marker(coordenada)
                    .addTo(this.rotasCamada)
                    .bindPopup(`
                        <b>${bairro}</b><br>
                        Ponto ${index + 1} da Rota<br>
                        <small>Coordenadas: ${coordenada[0].toFixed(4)}, ${coordenada[1].toFixed(4)}</small>
                    `);
            });

            // Adicionar ao grupo de layers
            this.rotasCamada.addLayer(polilinha);
        });
    }

    configurarEventos() {
        // Evento para cards de rota
        document.querySelectorAll('.rota-card').forEach(card => {
            card.addEventListener('click', () => {
                this.selecionarRota(card);
            });
        });

        // Evento para botões "Ver no Mapa"
        document.querySelectorAll('.ver-no-mapa').forEach(botao => {
            botao.addEventListener('click', (e) => {
                e.stopPropagation();
                const bairro = botao.dataset.bairro;
                this.focarNaRota(bairro);
            });
        });
    }

    selecionarRota(card) {
        // Remover classe ativa de todos os cards
        document.querySelectorAll('.rota-card').forEach(c => {
            c.classList.remove('ativo');
        });

        // Adicionar classe ativa ao card clicado
        card.classList.add('ativo');

        const bairro = card.dataset.bairro;
        this.focarNaRota(bairro);
    }

    async focarNaRota(bairro) {
        await this.carregarRotas(bairro);
        
        // Centralizar mapa na primeira coordenada da rota
        const rotas = await this.obterDadosRotas(bairro);
        if (rotas[bairro] && rotas[bairro].coordenadas.length > 0) {
            this.mapa.setView(rotas[bairro].coordenadas[0], 15);
        }
    }

    // Método para calcular rota entre dois pontos
    calcularRota(origem, destino) {
        // Em uma implementação real, usaria uma API de roteamento como OSRM
        console.log('Calculando rota de:', origem, 'para:', destino);
    }
}

// Inicializar mapa quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    window.mapaRotas = new MapaRotas();
    
    // Configurar busca em tempo real
    const inputBairro = document.querySelector('input[name="bairro"]');
    if (inputBairro) {
        let timeout;
        inputBairro.addEventListener('input', function(e) {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                window.mapaRotas.carregarRotas(e.target.value);
            }, 500);
        });
    }
});