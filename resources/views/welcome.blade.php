@extends(app('App\Services\ThemeService')->getThemeLayout())

@section('content')
<!-- Theme-specific Hero Section -->
@include(app('App\Services\ThemeService')->getThemeHero())
@endsection
