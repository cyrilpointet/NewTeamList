<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TeamList2 Reset Password</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<div class="bg-gray-200" style="min-height: 100vh">
    <div class="container mx-auto pt-8">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="bg-orange-200 p-2">
                <h1 class="text-2xl text-center font-bold">
                    Team List 2
                </h1>
            </div>

            <div class="p-6">
                <h2 class="text-xl mb-6">Réinitialiser mon mot de passe</h2>

                @if(isset($errorMessage))
                    <div class="my-6 p-4 bg-red-800 text-white rounded-lg">
                        <p>{{$errorMessage}}</p>
                    </div>
                @endif

                <form method="POST" action="/password-reset/{{$resetToken}}">
                    @csrf
                    <input type="hidden" name="resetToken" value="{{ $resetToken }}" />

                    <label for="email" class="block">
                        <span class="block text-sm">Email</span>
                        <input
                           type="email"
                           name="email"
                           value="{{old('email')}}"
                           class="outline-0 border-b border-b-gray-400 transition w-full py-1">
                        @error('email')
                        <span class="block text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </label>

                    <label for="password" class="mt-4 block">
                        <span class="block text-sm">Nouveau mot de passe</span>
                        <input name="password"
                           type="password"
                           class="outline-0 border-b border-b-gray-400 transition w-full py-1">
                        @error('password')
                        <span class="block text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </label>

                    <label for="password_confirmation" class="mt-4 block">
                        <span class="block text-sm">Répéter mon mot de passe</span>
                        <input name="password_confirmation"
                               type="password"
                               class="outline-0 border-b border-b-gray-400 transition w-full py-1">
                        @error('password')
                        <span class="block text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </label>

                    <div class="flex justify-center mt-4">
                        <button
                            class="bg-orange-200 px-4 py-2 rounded-lg transition flex items-center gap-2 overflow-hidden relative"
                            type="submit"
                        >
                            Valider
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
