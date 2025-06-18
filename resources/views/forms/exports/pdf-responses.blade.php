<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Form Responses: {{ $form->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 20px;
            margin: 0 0 5px 0;
        }
        .meta {
            color: #666;
            font-size: 11px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 11px;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .response {
            page-break-inside: avoid;
            margin-bottom: 20px;
        }
        .response-header {
            background-color: #f8f8f8;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #eee;
        }
        .page-break {
            page-break-after: always;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
            padding: 5px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Form Responses: {{ $form->name }}</h1>
        <div class="meta">
            Project: {{ $project->name }} | 
            Form ID: {{ $form->id }} |
            Total Responses: {{ $responses->count() }} |
            Generated: {{ $generated_date }}
        </div>
    </div>

    @foreach($responses as $index => $response)
        <div class="response">
            <div class="response-header">
                <strong>Response #{{ $response->id }}</strong> from 
                {{ $response->guest_name ?: 'Anonymous' }}
                @if($response->guest_email) ({{ $response->guest_email }}) @endif
                @if($response->guest_phone) | {{ $response->guest_phone }} @endif
                <br>
                Submitted on {{ $response->created_at->format('F j, Y \a\t H:i:s') }}
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th width="40%">Question</th>
                        <th width="60%">Response</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($components as $component)
                        <tr>
                            <td>
                                {{ $component->label }}
                                @if($component->required)
                                    *
                                @endif
                            </td>
                            <td>
                                @if(isset($response->responses[$component->id]))
                                    @if(is_array($response->responses[$component->id]))
                                        {{ implode(', ', $response->responses[$component->id]) }}
                                    @else
                                        {{ $response->responses[$component->id] }}
                                    @endif
                                @else
                                    No response
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($index < $responses->count() - 1)
            <div class="page-break"></div>
        @endif
    @endforeach
    
    <div class="footer">
        {{ $form->name }} | Exported via Invoice Automation System | {{ $generated_date }}
    </div>
</body>
</html>