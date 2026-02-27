@php
    function moeda($valor) {
        return $valor ? 'R$ ' . number_format(floatval(preg_replace('/[^\d]/', '', $valor)) / 100, 2, ',', '.') : '-';
    }
@endphp

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
                            🏠 Simulação de Financiamento
                        </td>
                    </tr>

                    <!-- Conteúdo -->
                    <tr>
                        <td style="padding:30px; color:#333333;">

                            <p style="font-size:16px; margin-bottom:15px;">
                                <strong>Data do envio:</strong> {{ now()->format('d/m/Y H:i') }}
                            </p>

                            <h2 style="font-size:18px; color:#0d9488; border-bottom:1px solid #e0e0e0; padding-bottom:8px;">Dados Pessoais</h2>
                            <table width="100%" style="font-size:12px; margin-bottom:20px;">
                                <tr>
                                    <td><strong>Nome:</strong> {{ $data['nome'] ?? '-' }}</td>
                                    <td><strong>E-mail:</strong> {{ $data['email'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Telefone:</strong> {{ $data['telefone'] ?? '-' }}</td>
                                    <td><strong>Data de Nascimento:</strong> {{ $data['nasc'] ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Estado:</strong> {{ $data['estado'] ?? '-' }}</td>
                                    <td><strong>Cidade:</strong> {{ $data['cidade'] ?? '-' }}</td>
                                </tr>
                                <tr>                                    
                                    <td><strong>Quando pretende se mudar?</strong> {{ $data['tempo'] ?? '-' }}</td>
                                    <td><strong>Modalidade</strong> {{ $data['tipo_financiamento'] ?? '-' }}</td>
                                </tr>
                            </table>

                            <h2 style="font-size:18px; color:#0d9488; border-bottom:1px solid #e0e0e0; padding-bottom:8px;">Detalhes do Financiamento</h2>
                            @if (($data['tipo_financiamento'] ?? '') === 'Consórcio' )
                                <table width="100%" style="font-size:12px; margin-bottom:20px;">
                                    <tr>
                                        <td><strong>Valor da Carta de Crédito:</strong> {{ moeda($data['valorcarta'] ?? '') }}</td>
                                        <td><strong>Prazo da Carta:</strong> {{ $data['prazocarta'] ?? '-' }}</td>
                                    </tr>
                                </table>
                            @elseif (($data['tipo_financiamento'] ?? '') === 'Financiamento' )
                                <table width="100%" style="font-size:12px; margin-bottom:20px;">
                                    <tr>
                                        <td><strong>Tipo do Imóvel:</strong> {{ $data['tipoimovel'] ?? '-' }}</td>
                                        <td><strong>Característica:</strong> {{ $data['natureza'] ?? '-' }}</td>
                                    </tr>                                    
                                    <tr>
                                        <td><strong>Valor do Imóvel:</strong> {{ moeda($data['valor'] ?? '') }}</td>
                                        <td><strong>Valor de Entrada:</strong> {{ moeda($data['valor_entrada'] ?? '') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Renda Mensal:</strong> {{ moeda($data['renda'] ?? '') }}</td>
                                        <td><strong>Objetivo:</strong> {{ $data['oqueprecisa'] ?? '-' }}</td>
                                    </tr>
                                </table>
                            @endif                           
                        </td>
                    </tr>

                    <!-- Rodapé -->
                    <tr>
                        <td style="background-color:#f9fafb; padding:15px 30px; text-align:center; font-size:11px; color:#888;">
                            Este e-mail foi enviado automaticamente através do simulador de financiamento do site <strong>{{ config('app.name') }}</strong>.
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
