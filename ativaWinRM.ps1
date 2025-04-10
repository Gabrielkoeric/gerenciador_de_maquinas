# Executar como Administrador

# 1. Habilitar o WinRM
Enable-PSRemoting -Force

# 2. Permitir autenticação básica e conexões sem criptografia (atenção: não seguro fora da rede local)
Set-Item -Path WSMan:\localhost\Service\AllowUnencrypted -Value $true
Set-Item -Path WSMan:\localhost\Service\Auth\Basic -Value $true

# 3. Permitir conexões de qualquer IP (ajuste se necessário)
Set-Item -Path WSMan:\localhost\Client\TrustedHosts -Value "*"

# 4. Criar regra de firewall para permitir conexões WinRM na porta 5985 (HTTP)
New-NetFirewallRule -Name "WinRM_HTTP" -DisplayName "WinRM over HTTP" `
    -Enabled True -Profile Any -Direction Inbound -Protocol TCP -LocalPort 5985 -Action Allow

# 5. Reiniciar o serviço WinRM para aplicar as alterações
Restart-Service WinRM

Write-Host "WinRM configurado com autenticação básica e firewall liberado na porta 5985." -ForegroundColor Green