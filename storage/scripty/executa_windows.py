import sys
from winrm.protocol import Protocol

# Recebe os parâmetros do Laravel
ip = sys.argv[1]
usuario = sys.argv[2]
senha = sys.argv[3]
comando_completo = sys.argv[4]
dominio = sys.argv[5] if len(sys.argv) > 5 else ""

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

try:
    # Abre o shell e executa o comando recebido
    shell_id = p.open_shell()
    command_id = p.run_command(shell_id, "powershell", [comando_completo])
    _, stdout, stderr = p.get_command_output(shell_id, command_id)
    p.close_shell(shell_id)

    # Exibe a saída para o Laravel capturar
    print(stdout.decode('cp850', errors='replace').strip())
except Exception as e:
    print(f"Erro ao executar o comando: {str(e)}")