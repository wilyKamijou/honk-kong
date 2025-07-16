<h1>Verifica tu correo</h1>

<form method="POST" action="{{ route('verify.submit') }}">
    @csrf
    <label for="code">Código recibido:</label>
    <input type="text" name="code" required>
    <button type="submit">Verificar</button>
</form>

@if($errors->any())
    <p style="color:red;">{{ $errors->first('code') }}</p>
@endif
