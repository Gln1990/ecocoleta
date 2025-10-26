<?php
// includes/email.php
class EmailNotificacao {
    private $fromEmail;
    private $fromName;
    
    public function __construct() {
        $this->fromEmail = 'notificacoes@ecocoleta.com';
        $this->fromName = 'EcoColeta';
    }
    
    public function enviarNotificacaoColetaAgendada($destinatario, $nome, $dadosColeta) {
        $assunto = 'Coleta Agendada - EcoColeta';
        $mensagem = $this->templateEmailColetaAgendada($dadosColeta);
        $headers = $this->getHeaders();
        
        return $this->enviarEmail($destinatario, $assunto, $mensagem, $headers);
    }
    
    public function enviarNotificacaoColetaSolicitada($coletorEmail, $coletorNome, $dadosColeta) {
        $assunto = 'Nova Solicitação de Coleta - EcoColeta';
        $mensagem = $this->templateEmailNovaSolicitacao($dadosColeta);
        $headers = $this->getHeaders();
        
        return $this->enviarEmail($coletorEmail, $assunto, $mensagem, $headers);
    }
    
    public function enviarNotificacaoRota($destinatario, $nome, $dadosRota) {
        $assunto = 'Rota de Coleta da Semana - EcoColeta';
        $mensagem = $this->templateEmailRota($dadosRota);
        $headers = $this->getHeaders();
        
        return $this->enviarEmail($destinatario, $assunto, $mensagem, $headers);
    }
    
    private function enviarEmail($para, $assunto, $mensagem, $headers) {
        try {
            // Usando a função mail() nativa do PHP
            $resultado = mail($para, $assunto, $mensagem, $headers);
            
            if (!$resultado) {
                error_log("Falha ao enviar e-mail para: $para");
                return false;
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Erro ao enviar e-mail: " . $e->getMessage());
            return false;
        }
    }
    
    private function getHeaders() {
        $headers = "From: {$this->fromName} <{$this->fromEmail}>\r\n";
        $headers .= "Reply-To: {$this->fromEmail}\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        return $headers;
    }
    
    private function templateEmailColetaAgendada($dados) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #2ecc71; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 20px; }
                .info-box { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #2ecc71; }
                .footer { background: #34495e; color: white; padding: 15px; text-align: center; border-radius: 0 0 10px 10px; }
                .btn { display: inline-block; padding: 10px 20px; background: #2ecc71; color: white; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🌱 EcoColeta</h1>
                    <p>Coleta de Recicláveis Agendada</p>
                </div>
                <div class='content'>
                    <h2>Olá, {$dados['morador_nome']}!</h2>
                    <p>Sua coleta de recicláveis foi agendada com sucesso.</p>
                    
                    <div class='info-box'>
                        <h3>📋 Detalhes da Coleta</h3>
                        <p><strong>Data e Horário:</strong> {$dados['data_agendada']}</p>
                        <p><strong>Endereço:</strong> {$dados['endereco']}</p>
                        <p><strong>Materiais:</strong> {$dados['material']}</p>
                        <p><strong>Quantidade:</strong> {$dados['quantidade']}</p>
                        <p><strong>Coletor:</strong> {$dados['coletor_nome']}</p>
                    </div>
                    
                    <p><strong>📝 Observações:</strong> Mantenha os materiais separados e em local visível no dia da coleta.</p>
                </div>
                <div class='footer'>
                    <p>EcoColeta - Contribuindo para um planeta mais sustentável</p>
                    <p>Se tiver dúvidas, entre em contato: contato@ecocoleta.com</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
    
    private function templateEmailNovaSolicitacao($dados) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #3498db; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 20px; }
                .info-box { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #3498db; }
                .footer { background: #34495e; color: white; padding: 15px; text-align: center; border-radius: 0 0 10px 10px; }
                .btn { display: inline-block; padding: 10px 20px; background: #2ecc71; color: white; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🌱 EcoColeta</h1>
                    <p>Nova Solicitação de Coleta</p>
                </div>
                <div class='content'>
                    <h2>Olá, {$dados['coletor_nome']}!</h2>
                    <p>Você recebeu uma nova solicitação de coleta.</p>
                    
                    <div class='info-box'>
                        <h3>📋 Detalhes da Solicitação</h3>
                        <p><strong>Morador:</strong> {$dados['morador_nome']}</p>
                        <p><strong>Endereço:</strong> {$dados['endereco']}</p>
                        <p><strong>Materiais:</strong> {$dados['material']}</p>
                        <p><strong>Quantidade:</strong> {$dados['quantidade']}</p>
                        <p><strong>Telefone:</strong> {$dados['telefone']}</p>
                        <p><strong>Observações:</strong> {$dados['observacoes']}</p>
                    </div>
                    
                    <p style='text-align: center;'>
                        <a href='http://seudominio.com/agendar_coleta.php?id={$dados['coleta_id']}' class='btn'>
                            Agendar Esta Coleta
                        </a>
                    </p>
                </div>
                <div class='footer'>
                    <p>EcoColeta - Sistema de Coleta de Recicláveis</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
    
    private function templateEmailRota($dados) {
        $dias = implode(', ', $dados['dias_semana']);
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #e67e22; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
                .content { background: #f9f9f9; padding: 20px; }
                .info-box { background: white; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #e67e22; }
                .footer { background: #34495e; color: white; padding: 15px; text-align: center; border-radius: 0 0 10px 10px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>🌱 EcoColeta</h1>
                    <p>Rota de Coleta da Semana</p>
                </div>
                <div class='content'>
                    <h2>Olá, {$dados['morador_nome']}!</h2>
                    <p>Confira a rota de coleta para sua região esta semana.</p>
                    
                    <div class='info-box'>
                        <h3>🗓️ Rota de Coleta</h3>
                        <p><strong>Bairro/Região:</strong> {$dados['bairro']}</p>
                        <p><strong>Dias da Semana:</strong> {$dias}</p>
                        <p><strong>Horário:</strong> {$dados['horario']}</p>
                        <p><strong>Coletor:</strong> {$dados['coletor_nome']}</p>
                    </div>
                    
                    <p><strong>📍 Visualize no mapa:</strong> 
                    <a href='http://seudominio.com/rotas.php?bairro={$dados['bairro']}'>
                        Clique aqui para ver a rota no mapa
                    </a></p>
                    
                    <p>Lembre-se de deixar seus recicláveis preparados no dia da coleta!</p>
                </div>
                <div class='footer'>
                    <p>EcoColeta - Coleta programada para sua comodidade</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
?>