<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>HEC-STOCKAGE | AZURE</title>
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">📁 Fichiers sauvegardés</h1>

        @if (session('success'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
                class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                role="alert">
                <strong class="font-bold">Succès ! </strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('azure.upload') }}" method="POST" enctype="multipart/form-data"
            class="mb-8 bg-white p-6 rounded shadow">
            @csrf
            <label class="block mb-2 text-sm font-medium text-gray-700">Choisir un fichier :</label>
            <input type="file" name="fichier" class="block w-full border border-gray-300 rounded p-2 mb-4" required>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                ⬆️ Envoyer vers Azure
            </button>
        </form>

        <div x-data="{
            search: '',
            get hasVisibleRows() {
                return Array.from($refs.tbody.children).some(tr => tr.style.display !== 'none');
            }
        }">
            <div class="mb-4">
                <input type="text" placeholder="🔍 Rechercher un fichier..."
                    class="block w-1/3 rounded-md bg-white py-2 text-base text-gray-900 outline outline-1 outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:pl-2 sm:text-sm/6"
                    x-model="search" />
            </div>

            <div class="mt-8 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                            Titre du document
                                        </th>
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200 bg-white" x-ref="tbody">
                                    @foreach ($files as $file)
                                        <tr
                                            x-show="{{ json_encode(strtolower($file['name'])) }}.includes(search.toLowerCase())">
                                            <td
                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                {{ $file['name'] }}
                                            </td>
                                            <td
                                                class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                <div class="flex items-center justify-end space-x-4">
                                                    <a href="{{ $file['url'] }}"
                                                        class="text-indigo-600 hover:text-indigo-900" target="_blank">
                                                        Consulter
                                                    </a>
                                                    <form action="{{ route('azure.destroy', $file['name']) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Supprimer ce fichier ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                                            Supprimer
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Message si aucun fichier trouvé -->
                            <div x-show="!hasVisibleRows" class="py-4 text-center text-sm text-gray-500 italic">
                                Aucun fichier ne correspond à votre recherche.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>
