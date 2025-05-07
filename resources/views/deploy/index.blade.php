<x-layout title="Deploy">
    <div class="container py-5">

        <div class="row row-cols-1 row-cols-md-2 g-4 justify-content-center">
            <div class="col">
                <a href="{{ route('deploy.show', 'EscalaServer') }}" class="btn btn-primary btn-lg w-100 shadow-sm">
                    🚀 EscalaServer
                </a>
            </div>

            <div class="col">
                <a href="{{ route('deploy.show', 'EscalaSwarm') }}" class="btn btn-success btn-lg w-100 shadow-sm">
                    🐳 EscalaSwarm
                </a>
            </div>

            <div class="col">
                <a href="{{ route('deploy.show', 'EscalaWeb') }}" class="btn btn-info btn-lg text-white w-100 shadow-sm">
                    🌐 EscalaWeb
                </a>
            </div>

            <div class="col">
                <a href="{{ route('deploy.show', 'EscalaWebService') }}" class="btn btn-danger btn-lg w-100 shadow-sm">
                    🛠️ EscalaWebService
                </a>
            </div>
        </div>
    </div>
</x-layout>
