import sys
from winrm.protocol import Protocol

# Recebe os parâmetros do Laravel
ip = sys.argv[1]
usuario = sys.argv[2]
senha = sys.argv[3]
servico = sys.argv[4]
acao = sys.argv[5]
dominio = sys.argv[6] if len(sys.argv) > 6 else ""

# Se tiver domínio, adiciona ao usuário
if dominio:
    usuario = f"{dominio}\\{usuario}"

# Configura a conexão com WinRM no Windows
p = Protocol(
    endpoint=f"http://{ip}:5985/wsman",
    transport="basic",
    username=usuario,
    password=senha,
    server_cert_validation="ignore"
)

# Comando a ser executado
comandos = {
    "start": f"Start-Service -Name {servico}",
    "stop": f"Stop-Service -Name {servico}",
    "restart": f"Restart-Service -Name {servico}",
    "status": f"powershell -Command \"(Get-Service -Name {servico}).Status\""
}


if acao in comandos:
    shell_id = p.open_shell()
    command_id = p.run_command(shell_id, "powershell", [comandos[acao]])
    _, stdout, stderr = p.get_command_output(shell_id, command_id)
    p.close_shell(shell_id)

    # Exibe a saída para o Laravel capturar
    print(stdout.decode('cp850', errors='replace').strip())

else:
    print("Ação inválida")
