<!DOCTYPE html>
<html lang="es-MX">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Gym Rat es tu diario de entrenamiento para registrar series, organizar rutinas y seguir tu progreso.">
        <x-inertia::head>
            <title>Gym Rat | Diario de entrenamiento</title>
        </x-inertia::head>
        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <x-inertia::app />
    </body>
</html>
