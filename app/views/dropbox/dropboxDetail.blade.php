@extends('layouts.master')
@section('content')

@for($i=0;$i<=count($imgSrc)-1;$i++)
	<img src="{{ $imgSrc[$i] }}"/>
@endfor

@stop