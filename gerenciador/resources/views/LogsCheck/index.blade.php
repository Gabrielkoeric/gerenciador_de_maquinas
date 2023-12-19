<x-layout title="Logs de Check-in e Check-out">
    <a href="{{route('home.index')}}" class="btn btn-dark my-3 pr">Home</a>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#checkIn" role="tab" aria-controls="checkIn" aria-selected="true">Check-In</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#checkOut" role="tab" aria-controls="checkOut" aria-selected="false">Check-Out</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="checkIn" role="tabpanel" aria-labelledby="home-tab">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Usuario</th>
                    <th scope="col">Porteiro</th>
                    <th scope="col">IP Porteiro</th>
                    <th scope="col">Data e Hora</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($resultCheckins as $resultCheckin)
                    <tr>
                        <td>{{ $resultCheckin->email }}</a></td>
                        <td>{{ $resultCheckin->portaria }}</a></td>
                        <td>{{ $resultCheckin->ip_address }}</td>
                        <td>{{ $resultCheckin->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $resultCheckins->links('pagination::bootstrap-4') }}
        </div>
        <div class="tab-pane fade" id="checkOut" role="tabpanel" aria-labelledby="profile-tab">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Usuario</th>
                    <th scope="col">Porteiro</th>
                    <th scope="col">IP Porteiro</th>
                    <th scope="col">Data e Hora</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($resultCheckouts as $resultCheckout)
                    <tr>
                        <td>{{ $resultCheckout->email }}</a></td>
                        <td>{{ $resultCheckout->portaria }}</a></td>
                        <td>{{ $resultCheckout->ip_address }}</td>
                        <td>{{ $resultCheckout->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $resultCheckouts->links('pagination::bootstrap-4') }}
        </div>
    </div>
</x-layout>



