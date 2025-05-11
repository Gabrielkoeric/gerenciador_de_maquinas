import psutil
import json
import requests

def check_services_by_names(partial_names):
    statuses = []
    
    for service in psutil.win_service_iter():
        for name in partial_names:
            if name.lower() in service.name().lower():  # Verifica se o nome do serviço contém qualquer uma das palavras
                status = service.status()
                if status.lower() != "running":  # Só adiciona serviços cujo status é diferente de "running"
                    statuses.append({
                        "servico": service.name(),
                        "status": status
                    })
                break  # Se já encontrar, não precisa continuar verificando outras palavras
    
    return statuses

# Passe a lista de strings que deseja procurar nos nomes dos serviços
partial_names = ['teste', 'spooler']  # Serviços que contenham 'teste' ou 'server'
status = check_services_by_names(partial_names)

# Se não houver serviços com status diferente de "running", não envia nada
if status:
    # URL da API para enviar o JSON
    url = "https://192.168.0.89/api/status_servico"

    # Envia o JSON para a API via POST
    try:
        response = requests.post(url, json=status, verify=False)  # `verify=False` ignora problemas com SSL (use com cuidado)
        if response.status_code == 200:
            print("Status dos serviços enviados com sucesso!")
        else:
            print(f"Erro ao enviar os dados. Status Code: {response.status_code}")
    except requests.exceptions.RequestException as e:
        print(f"Ocorreu um erro ao tentar enviar os dados: {e}")
else:
    print("Nenhum serviço com status diferente de 'running' encontrado.")
