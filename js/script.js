// js/script.js
// Funções gerais do sistema

// Máscara para CEP
function mascaraCEP(cep) {
    cep = cep.replace(/\D/g, '');
    cep = cep.replace(/^(\d{5})(\d)/, '$1-$2');
    return cep;
}

// Buscar endereço via CEP
async function buscarEnderecoPorCEP(cep) {
    if (cep.length === 9) {
        try {
            const response = await fetch(`https://viacep.com.br/ws/${cep.replace('-', '')}/json/`);
            const data = await response.json();
            
            if (!data.erro) {
                document.querySelector('input[name="endereco"]').value = data.logradouro;
                document.querySelector('input[name="cidade"]').value = data.localidade;
                document.querySelector('input[name="estado"]').value = data.uf;
            }
        } catch (error) {
            console.error('Erro ao buscar CEP:', error);
        }
    }
}

// Inicialização
document.addEventListener('DOMContentLoaded', function() {
    // Aplicar máscara de CEP
    const cepInput = document.querySelector('input[name="cep"]');
    if (cepInput) {
        cepInput.addEventListener('input', function(e) {
            e.target.value = mascaraCEP(e.target.value);
            buscarEnderecoPorCEP(e.target.value);
        });
    }
    
    // Validação de formulários
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let valid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.style.borderColor = 'red';
                } else {
                    field.style.borderColor = '';
                }
            });
            
            if (!valid) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos obrigatórios.');
            }
        });
    });
});