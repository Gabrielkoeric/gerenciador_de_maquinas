param(
    [string]$nomevm
)
get-RDUserSession | where  SessionState -Like 'STATE_DISCONNECTED' | foreach { Invoke-RDUserLogoff -HostServer $nomevm -UnifiedSessionID $_.UnifiedSessionId -Force}
