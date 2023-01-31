<x-layout title="Novo usuário">
    <form method="post">
        @csrf

        <div class="form-group mb-2">
            <label for="name" class="form-label">Nome</label>
            <input type="text"
                   name="name"
                   id="name"
                   class="form-control"
                   value="{{ old('name') }}" >
        </div>

        <div class="form-group mb-2">
            <label for="email" class="form-label">E-mail</label>
            <input type="email"
                   name="email"
                   id="email"
                   class="form-control"
                   value="{{ old('email') }}" >
        </div>

        <div class="form-group mb-2">
            <label for="password" class="form-label">Senha</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>

        <div class="form-group mb-2">
            <label for="password_confirmation" class="form-label">Confirme a senha</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        </div>

        <button class="btn btn-primary mt-2">Registrar</button>
    </form>
</x-layout>
