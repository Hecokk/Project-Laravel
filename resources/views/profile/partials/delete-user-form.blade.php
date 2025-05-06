@php
// File baru untuk mengganti file sebelumnya
@endphp
<section>
    <p class="text-muted mb-4">
        {{ __('Setelah akun Anda dihapus, semua data dan sumber daya yang terkait akan dihapus secara permanen.') }}
    </p>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
        {{ __('Hapus Akun') }}
    </button>

    <!-- Modal Konfirmasi Penghapusan -->
    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmUserDeletionModalLabel">{{ __('Apakah Anda yakin ingin menghapus akun?') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p>{{ __('Setelah akun Anda dihapus, semua data dan sumber daya yang terkait akan dihapus secara permanen. Silakan masukkan password Anda untuk mengonfirmasi bahwa Anda benar-benar ingin menghapus akun Anda secara permanen.') }}</p>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password" name="password" type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" required>
                            @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Batal') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('Hapus Akun') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>