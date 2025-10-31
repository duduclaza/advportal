<?php
/**
 * Helper para envio de emails usando PHPMailer
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailHelper {
    
    /**
     * Envia email com código de confirmação
     */
    public static function enviarCodigoConfirmacao($email, $nome, $codigo) {
        $assunto = 'Código de Confirmação - AdvPortal';
        $mensagem = self::templateCodigoConfirmacao($nome, $codigo);
        
        return self::enviar($email, $nome, $assunto, $mensagem);
    }
    
    /**
     * Envia email de boas-vindas
     */
    public static function enviarBoasVindas($email, $nome) {
        $assunto = 'Bem-vindo ao AdvPortal';
        $mensagem = self::templateBoasVindas($nome);
        
        return self::enviar($email, $nome, $assunto, $mensagem);
    }
    
    /**
     * Envia email de notificação de novo caso
     */
    public static function notificarNovoCaso($email, $nome, $numeroCaso, $titulo) {
        $assunto = 'Novo Caso Atribuído - ' . $numeroCaso;
        $mensagem = self::templateNovoCaso($nome, $numeroCaso, $titulo);
        
        return self::enviar($email, $nome, $assunto, $mensagem);
    }
    
    /**
     * Função genérica de envio de email
     */
    private static function enviar($destinatario, $nomeDestinatario, $assunto, $mensagem) {
        $mail = new PHPMailer(true);
        
        try {
            // Configurações do servidor
            $mail->isSMTP();
            $mail->Host = MAIL_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = MAIL_USERNAME;
            $mail->Password = MAIL_PASSWORD;
            $mail->SMTPSecure = MAIL_ENCRYPTION;
            $mail->Port = MAIL_PORT;
            $mail->CharSet = 'UTF-8';
            
            // Remetente
            $mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);
            
            // Destinatário
            $mail->addAddress($destinatario, $nomeDestinatario);
            
            // Conteúdo
            $mail->isHTML(true);
            $mail->Subject = $assunto;
            $mail->Body = $mensagem;
            $mail->AltBody = strip_tags($mensagem);
            
            $mail->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Erro ao enviar email: {$mail->ErrorInfo}");
            return false;
        }
    }
    
    /**
     * Template HTML para código de confirmação
     */
    private static function templateCodigoConfirmacao($nome, $codigo) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #2563eb; color: white; padding: 20px; text-align: center; }
                .content { background: #f9fafb; padding: 30px; }
                .code { background: white; border: 2px dashed #2563eb; padding: 20px; text-align: center; font-size: 32px; font-weight: bold; color: #2563eb; letter-spacing: 10px; margin: 20px 0; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>AdvPortal</h1>
                </div>
                <div class='content'>
                    <h2>Olá, {$nome}!</h2>
                    <p>Você solicitou um código de confirmação para acessar o AdvPortal.</p>
                    <p>Use o código abaixo para confirmar seu acesso:</p>
                    <div class='code'>{$codigo}</div>
                    <p><strong>Este código expira em 15 minutos.</strong></p>
                    <p>Se você não solicitou este código, ignore este email.</p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " AdvPortal - Sistema de Gestão de Processos Jurídicos</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
    
    /**
     * Template HTML para boas-vindas
     */
    private static function templateBoasVindas($nome) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #2563eb; color: white; padding: 20px; text-align: center; }
                .content { background: #f9fafb; padding: 30px; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Bem-vindo ao AdvPortal!</h1>
                </div>
                <div class='content'>
                    <h2>Olá, {$nome}!</h2>
                    <p>Sua conta foi criada com sucesso no AdvPortal.</p>
                    <p>Você agora tem acesso ao sistema de gestão de processos jurídicos.</p>
                    <p>Para acessar, visite: <a href='" . APP_URL . "'>" . APP_URL . "</a></p>
                    <p>Se tiver alguma dúvida, entre em contato com nosso suporte.</p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " AdvPortal - Sistema de Gestão de Processos Jurídicos</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
    
    /**
     * Template HTML para notificação de novo caso
     */
    private static function templateNovoCaso($nome, $numeroCaso, $titulo) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #2563eb; color: white; padding: 20px; text-align: center; }
                .content { background: #f9fafb; padding: 30px; }
                .caso { background: white; border-left: 4px solid #2563eb; padding: 15px; margin: 20px 0; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>AdvPortal</h1>
                </div>
                <div class='content'>
                    <h2>Olá, {$nome}!</h2>
                    <p>Um novo caso foi atribuído a você:</p>
                    <div class='caso'>
                        <p><strong>Número:</strong> {$numeroCaso}</p>
                        <p><strong>Título:</strong> {$titulo}</p>
                    </div>
                    <p>Acesse o sistema para visualizar mais detalhes.</p>
                    <p><a href='" . APP_URL . "' style='background: #2563eb; color: white; padding: 10px 20px; text-decoration: none; display: inline-block; border-radius: 5px;'>Acessar AdvPortal</a></p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " AdvPortal - Sistema de Gestão de Processos Jurídicos</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
