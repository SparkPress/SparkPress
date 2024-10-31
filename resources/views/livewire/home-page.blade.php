<div>
    <h1>SparkPress</h1>
    <p>Example Output</p>
    @if(auth()->check())
        <p><a href="{{ route("logout") }}" wire:navigate>Logout</a></p>
    @else
        <p><a href="{{ route("login") }}" wire:navigate>Login</a></p>
    @endif
</div>
