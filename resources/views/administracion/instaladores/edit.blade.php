@extends('layouts.dashboard')



@section('page-title', 'Editar Instalador')
@section('page-subtitle', 'Actualizar registro')

@section('content')
<div class="p-6">
  <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 max-w-3xl">
    <form action="{{ route('administracion.instaladores.update', $instalador) }}" method="POST"
          class="space-y-6 js-confirm-submit"
          data-title="Confirmar actualización"
          data-text="Se actualizarán los datos del instalador."
          data-icon="question"
          data-confirm="Actualizar"
          data-cancel="Cancelar">
      @csrf
      @method('PUT')

      @include('administracion.instaladores._form', ['instalador' => $instalador])

      <div class="flex items-center gap-3">
        <button class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">Guardar cambios</button>
        <a href="{{ route('administracion.instaladores.index') }}" class="px-4 py-2 rounded-xl border">Cancelar</a>
      </div>
    </form>
  </div>
</div>
@endsection


@push('scripts')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Toast global
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 2500,
  timerProgressBar: true,
});

// Mostrar flash success/error como toast
@if(session('success'))
  Toast.fire({ icon: 'success', title: @json(session('success')) });
@endif
@if($errors->any())
  Toast.fire({ icon: 'error', title: 'Revisa los datos del formulario' });
@endif

// Confirmación genérica para forms con .js-confirm
document.addEventListener('click', async (e) => {
  const btn = e.target.closest('.js-confirm');
  if (!btn) return;

  e.preventDefault();
  const form = btn.closest('form');
  const title = btn.dataset.title || '¿Estás seguro?';
  const text = btn.dataset.text || 'Esta acción no se puede deshacer.';
  const icon = btn.dataset.icon || 'warning';
  const confirmButtonText = btn.dataset.confirm || 'Sí, continuar';
  const cancelButtonText = btn.dataset.cancel || 'Cancelar';

  const res = await Swal.fire({
    title, text, icon,
    showCancelButton: true,
    confirmButtonText,
    cancelButtonText,
    reverseButtons: true,
    focusCancel: true,
  });

  if (res.isConfirmed) form.submit();
});

// Confirmar envío de formularios de creación/edición (.js-confirm-submit)
document.addEventListener('submit', async (e) => {
  const form = e.target;
  if (!form.classList.contains('js-confirm-submit')) return;

  e.preventDefault();

  const title = form.dataset.title || 'Confirmar guardado';
  const text = form.dataset.text || 'Se guardarán los cambios.';
  const icon = form.dataset.icon || 'question';
  const confirmButtonText = form.dataset.confirm || 'Guardar';
  const cancelButtonText = form.dataset.cancel || 'Cancelar';

  const res = await Swal.fire({
    title, text, icon,
    showCancelButton: true,
    confirmButtonText,
    cancelButtonText,
    reverseButtons: true,
    focusCancel: true,
  });

  if (res.isConfirmed) form.submit();
});
</script>
@endpush

