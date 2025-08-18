@props(['company', 'size' => '50px', 'class' => ''])

@if($company->logo && Storage::disk('public')->exists($company->logo))
    <img src="{{ asset('storage/' . $company->logo) }}" 
         alt="{{ $company->name }} logo" 
         class="img-thumbnail {{ $class }}" 
         style="width: {{ $size }}; height: {{ $size }}; object-fit: cover;"
         onerror="this.onerror=null; this.src='{{ asset('images/default-company.svg') }}'; this.classList.remove('img-thumbnail'); this.classList.add('default-logo');">
@else
    <img src="{{ asset('images/default-company.svg') }}" 
         alt="Default company logo" 
         class="default-logo {{ $class }}" 
         style="width: {{ $size }}; height: {{ $size }};">
@endif
