<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Simulação de Financiamento</title>
</head>
<body style="margin:0; padding:0; font-family:'Segoe UI', Arial, sans-serif; background-color:#f5f5f5;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f5f5f5; padding:30px 0;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.1);">
                    <!-- Cabeçalho -->
                    <tr>
                        <td style="background-color:#0d9488; color:#ffffff; padding:20px 30px; font-size:22px; font-weight:600;">
                            🏠 Finalizar Reserva
                        </td>
                    </tr>

                    <!-- Conteúdo -->
                    <tr>
                        <td style="padding:30px; color:#333333;">

                            <p>Ola, {{ \App\Helpers\Renato::getPrimeiroNome($reservation['guest_name']) ?? '-' }}<br> 
                                Agora é hora de finalizar sua reserva.<br>
                                Segue abaixo os detalhes e o link para finalizar a reserva.<br>
                            </p>

                            <p style="font-size:16px; margin-bottom:15px;">
                                <strong>Data da reserva:</strong> {{ $reservation['created_at']->format('d/m/Y H:i') }}
                            </p>

                            <h2 style="font-size:18px; color:#0d9488; border-bottom:1px solid #e0e0e0; padding-bottom:8px;">Dados da Reserva</h2>
                            <table width="100%" style="font-size:12px; margin-bottom:20px;">
                                <tr>
                                    <td><strong>Responsável:</strong> {{ $reservation['guest_name'] ?? '-' }}</td>
                                    <td><strong>E-mail:</strong> {{ $reservation['guest_email'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Telefone:</strong> {{ $reservation['guest_phone'] ?? '-' }}</td>
                                    <td></td>
                                </tr>                                
                            </table>

                            <h2 style="font-size:18px; color:#0d9488; border-bottom:1px solid #e0e0e0; padding-bottom:8px;">Dados do Imóvel</h2>
                            <table width="100%" style="font-size:12px; margin-bottom:10px;">
                                <tr>
                                    <td colspan="2"><strong>Imóvel:</strong> {{ $property['title'] ?? '-' }}</td>
                                </tr>                                    
                                <tr>
                                    <td><strong>checkin:</strong> {{ $reservation['check_in']->format('d/m/Y') }}</td>
                                    <td><strong>Checkout:</strong> {{ $reservation['check_out']->format('d/m/Y') }}</td>
                                </tr>
                            </table>                           
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:10px; color:#333333; text-align:center;">
                            <a href="{{ route('web.reservation.form', $reservation['review_token']) }}" style="background-color:#0d9488; color:#ffffff; padding:12px 30px; text-decoration:none; border-radius:4px; font-weight:600;">Finalizar Reserva</a>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="background-color:#f9fafb; padding:15px 30px; text-align:center; font-size:11px; color:#888;">                                                       
                            {{ config('app.name') }} © {{ date('Y') }}<br>
                            Desenvolvido por 
                            <a href="mailto:{{ env('DESENVOLVEDOR_EMAIL') }}" style="color:#0d9488; text-decoration:none;">
                                {{ env('DESENVOLVEDOR') }}
                            </a>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>
