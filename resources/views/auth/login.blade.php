<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login • Admin Panel</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
	<script src="https://cdn.tailwindcss.com"></script>
	<script>
		tailwind.config = {
			theme: {
				extend: {
					fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'Segoe UI', 'Roboto', 'Helvetica', 'Arial'] },
					colors: {
						brand: {
							DEFAULT: '#6366F1',
							dark: '#4F46E5'
						}
					}
				}
			}
		}
	</script>
</head>
<body class="min-h-screen bg-slate-50">
	<div class="relative isolate min-h-screen flex items-center justify-center overflow-hidden">
		<div class="pointer-events-none absolute inset-0 -z-10 bg-gradient-to-br from-brand/10 via-fuchsia-200/20 to-cyan-200/20"></div>
		<div class="w-full max-w-md mx-auto px-6 py-10">
			<div class="mb-8 text-center">
				<div class="mx-auto h-12 w-12 rounded-xl bg-brand/10 text-brand flex items-center justify-center">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
						<path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.03 6.22a.75.75 0 0 1 1.06 0l3.75 3.75a.75.75 0 0 1 0 1.06l-3.75 3.75a.75.75 0 1 1-1.06-1.06l2.47-2.47H8.25a.75.75 0 0 1 0-1.5h5.19l-2.47-2.47a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
					</svg>
				</div>
				<h1 class="mt-4 text-2xl font-semibold tracking-tight text-slate-900">Welcome back</h1>
				<p class="mt-1 text-slate-600">Sign in to your Admin Panel</p>
			</div>

			@if ($errors->any())
				<div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
					<ul class="list-disc list-inside">
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif

			<form method="POST" action="{{ url('login') }}" class="space-y-5">
				@csrf
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div>
					<label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
					<input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email"
						class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder:text-slate-400 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/30" placeholder="you@example.com" />
				</div>

				<div>
					<div class="mb-2 flex items-center justify-between">
						<label for="password" class="block text-sm font-medium text-slate-700">Password</label>
						<a href="#" class="text-sm text-brand hover:text-brand-dark">Forgot?</a>
					</div>
					<input id="password" name="password" type="password" required autocomplete="current-password"
						class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder:text-slate-400 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/30" placeholder="••••••••" />
				</div>

				<div class="flex items-center justify-between">
					<label class="inline-flex items-center gap-2 text-sm text-slate-600">
						<input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-brand focus:ring-brand/30" />
						<span>Remember me</span>
					</label>
				</div>

				<button type="submit" class="group relative inline-flex w-full items-center justify-center gap-2 rounded-lg bg-brand px-4 py-2.5 font-medium text-white shadow-sm transition hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-brand/30">
					<svg class="h-5 w-5 opacity-90 transition group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18m0 0-6-6m6 6-6 6"></path></svg>
					<span>Sign in</span>
				</button>
			</form>


		</div>
	</div>
</body>
</html> 