@component('mail::message')
    Compra realizada no valor de R$: {{ $valor }}
    Status de pagamento: {{ $status }}
    @if ($status === "Aguardando Pagamento")
        Link de pagamento: [Realizar Pagamento]({{ $link }})
    @endif
@endcomponent

