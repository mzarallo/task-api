<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        * {
            font-family: Helvetica, sans-serif;
        }
        .w-100 {
            width: 100%;
        }
        table {
            font-family: Helvetica, sans-serif;
            font-size: 12px;
            font-weight: 200;
        }
        table td {
            font-weight: 200;
        }
    </style>
</head>
<body>
    <h3>Board #{{$board->id}}: {{ $board->name }}</h3>
    <hr>
    <table class="w-100" aria-describedby="">
        <tbody>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Stage</th>
                <th>Start date</th>
                <th>End date</th>
                <th>Tags</th>
                <th>Autor</th>
            </tr>
        </tbody>
        <thead>
        @forelse($board->stages as $stage)
            @foreach($stage->tasks as $task)
            <tr>
                <td>{{ $task->id }}</td>
                <td>{{ $task->name }}</td>
                <td>{{ mb_substr($task->description, 0, 30).'...' ?? '' }}</td>
                <td>{{ $task->stage->name }}</td>
                <td>{{ $task->start_date?->format('d-m-Y H:m') ?? '-' }}</td>
                <td>{{ $task->end_date?->format('d-m-Y H:m') ?? '-' }}</td>
                <td>{{ '-' }}</td>
                <td>{{ $task->author_full_name }}</td>
            </tr>
            @endforeach
        @empty
            <tr>
                <td colspan="9">
                    No existen tareas en el tablero
                </td>
            </tr>
        @endforelse
        </thead>
    </table>
</body>
</html>
