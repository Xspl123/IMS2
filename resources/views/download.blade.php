@extends('layouts.base')

@section('caption', '')

@section('title', 'Download PDFs')

@section('content')
<div class="container">
    <h1>Download PDFs</h1>
    <p>Click the links below to download the PDFs:</p>
    <a href="{{ route('download.com') }}" class="download-button" download>Download Company PDF</a>
    <a href="{{ route('download.cust') }}" class="download-button" download>Download Customer PDF</a>
    <br>
    <a href="{{ url()->previous() }}" class="back-button">Go Back</a>
</div>
@endsection

<style>
    .container {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        margin: 20px auto;
        max-width: 400px;
        text-align: center;
    }

    h1 {
        color: #333333;
    }

    .download-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007BFF;
        color: #ffffff;
        text-decoration: none;
        border-radius: 5px;
        margin: 10px;
        transition: background-color 0.3s;
    }

    .download-button:hover {
        background-color: #0056b3;
        color: #ffffff;
    }

    .back-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #333333;
        color: #ffffff;
        text-decoration: none;
        border-radius: 5px;
        margin: 10px;
        transition: background-color 0.3s;
    }

    .back-button:hover {
        background-color: #555555;
    }

    p {
        color: #666666;
    }
</style>

