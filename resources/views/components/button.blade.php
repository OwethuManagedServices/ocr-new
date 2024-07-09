<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-2 bg-blue border border-greydark rounded-md font-semibold text-sm text-dark tracking-widest shadow-sm text-light hover:bg-bluemedium focus:outline-none focus:ring-2 focus:ring-bluemedium focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
