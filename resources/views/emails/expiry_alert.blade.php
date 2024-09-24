<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>備蓄品の期限が近づいています</title>
</head>
<body>
    <h1>備蓄品の期限が近づいています</h1>
    <p>以下の備蓄品の期限が近づいています:</p>
    <ul>
        @foreach ($items as $item)
            <li>{{ $item->name }} (期限: {{ $item->date }})</li>
        @endforeach
    </ul>
</body>
</html>
