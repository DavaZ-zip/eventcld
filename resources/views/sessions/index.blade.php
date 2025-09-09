@extends('layouts.app')

@section('title', 'Manajemen Sesi')

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title">Daftar Sesi</h3>
            <button class="btn btn-primary" onclick="openCreateModal()">
                <i class="fas fa-plus"></i>
                Tambah Sesi
            </button>
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Judul Sesi</th>
                        <th>Acara</th>
                        <th>Pembicara</th>
                        <th>Waktu</th>
                        <th>Lokasi</th>
                        <th>Peserta</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $session)
                    <tr data-session-id="{{ $session->id }}">
                        <td>
                            <div style="font-weight: 600;">{{ $session->title }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">
                                {{ Str::limit($session->description, 50) }}
                            </div>
                        </td>
                        <td>{{ $session->event->title }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <div style="width: 24px; height: 24px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 600;">
                                    {{ substr($session->speaker->name, 0, 1) }}
                                </div>
                                <span>{{ $session->speaker->name }}</span>
                            </div>
                        </td>
                        <td>
                            <div>{{ $session->start_time->format('d M Y') }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">
                                {{ $session->start_time->format('H:i') }} - {{ $session->end_time->format('H:i') }}
                            </div>
                        </td>
                        <td>{{ $session->location }}</td>
                        <td>
                            <span class="badge badge-info">
                                {{ $session->participants->count() }}/{{ $session->max_participants }}
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.25rem;">
                                <button class="btn btn-warning btn-sm" onclick="editSession({{ $session->id }})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-info btn-sm" onclick="viewSession({{ $session->id }})" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteSession({{ $session->id }})" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            <i class="fas fa-clock" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                            <div>Belum ada sesi</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($sessions->hasPages())
    <div class="card-body">
        {{ $sessions->links() }}
    </div>
    @endif
</div>

<!-- Create/Edit Session Modal -->
<div id="sessionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="sessionModalTitle">Tambah Sesi Baru</h3>
            <button class="modal-close" onclick="closeModal('sessionModal')">&times;</button>
        </div>
        <form id="sessionForm">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="event_id">Acara *</label>
                    <select class="form-control form-select" id="event_id" name="event_id" required>
                        <option value="">-- Pilih Acara --</option>
                        @foreach($events as $event)
                        <option value="{{ $event->id }}">{{ $event->title }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="title">Judul Sesi *</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="description">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label" for="start_time">Waktu Mulai *</label>
                        <input type="datetime-local" class="form-control" id="start_time" name="start_time" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="end_time">Waktu Selesai *</label>
                        <input type="datetime-local" class="form-control" id="end_time" name="end_time" required>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label" for="speaker_id">Pembicara *</label>
                        <select class="form-control form-select" id="speaker_id" name="speaker_id" required>
                            <option value="">-- Pilih Pembicara --</option>
                            @foreach($speakers as $speaker)
                            <option value="{{ $speaker->id }}">{{ $speaker->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="location">Lokasi *</label>
                        <input type="text" class="form-control" id="location" name="location" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="max_participants">Maksimal Peserta *</label>
                    <input type="number" class="form-control" id="max_participants" name="max_participants" min="1" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('sessionModal')">Batal</button>
                <button type="submit" class="btn btn-primary" id="sessionSubmitBtn">
                    <i class="fas fa-save"></i>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Session Modal -->
<div id="viewSessionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Detail Sesi</h3>
            <button class="modal-close" onclick="closeModal('viewSessionModal')">&times;</button>
        </div>
        <div class="modal-body" id="sessionDetails">
            <!-- Content will be loaded dynamically -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('viewSessionModal')">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentSessionId = null;
let isEditing = false;

function openCreateModal() {
    isEditing = false;
    currentSessionId = null;
    $('#sessionModalTitle').text('Tambah Sesi Baru');
    $('#sessionForm')[0].reset();
    openModal('sessionModal');
}

function editSession(id) {
    isEditing = true;
    currentSessionId = id;
    $('#sessionModalTitle').text('Edit Sesi');
    
    showLoading($('#sessionSubmitBtn'));
    
    $.get(`/sessions/${id}`)
        .done(function(response) {
            $('#event_id').val(response.event_id);
            $('#title').val(response.title);
            $('#description').val(response.description || '');
            $('#start_time').val(response.start_time.replace(' ', 'T').substring(0, 16));
            $('#end_time').val(response.end_time.replace(' ', 'T').substring(0, 16));
            $('#speaker_id').val(response.speaker_id);
            $('#location').val(response.location);
            $('#max_participants').val(response.max_participants);
            
            hideLoading($('#sessionSubmitBtn'));
            openModal('sessionModal');
        })
        .fail(function() {
            hideLoading($('#sessionSubmitBtn'));
            showAlert('Gagal memuat data sesi', 'error');
        });
}

function viewSession(id) {
    $('#sessionDetails').html('<div class="loading"><div class="spinner"></div> Memuat...</div>');
    openModal('viewSessionModal');
    
    $.get(`/sessions/${id}`)
        .done(function(session) {
            const startTime = new Date(session.start_time);
            const endTime = new Date(session.end_time);
            
            const html = `
                <div style="margin-bottom: 1.5rem;">
                    <h4 style="margin-bottom: 0.5rem;">${session.title}</h4>
                    <span class="badge badge-info">${session.event.title}</span>
                </div>
                
                ${session.description ? `
                <div style="margin-bottom: 1rem;">
                    <strong>Deskripsi:</strong>
                    <p style="margin-top: 0.5rem; color: var(--text-muted);">${session.description}</p>
                </div>
                ` : ''}
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <strong>Waktu Mulai:</strong>
                        <div>${startTime.toLocaleString('id-ID')}</div>
                    </div>
                    <div>
                        <strong>Waktu Selesai:</strong>
                        <div>${endTime.toLocaleString('id-ID')}</div>
                    </div>
                </div>
                
                <div style="margin-bottom: 1rem;">
                    <strong>Pembicara:</strong>
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-top: 0.5rem;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                            ${session.speaker.name.charAt(0)}
                        </div>
                        <div>
                            <div style="font-weight: 600;">${session.speaker.name}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">${session.speaker.email}</div>
                        </div>
                    </div>
                </div>
                
                <div style="margin-bottom: 1rem;">
                    <strong>Lokasi:</strong> ${session.location}
                </div>
                
                <div style="margin-bottom: 1rem;">
                    <strong>Kapasitas:</strong> ${session.participants ? session.participants.length : 0}/${session.max_participants} peserta
                </div>
                
                <div style="display: flex; gap: 0.5rem; margin-top: 1.5rem;">
                    <button class="btn btn-primary btn-sm" onclick="closeModal('viewSessionModal'); editSession(${session.id})">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </div>
            `;
            
            $('#sessionDetails').html(html);
        })
        .fail(function() {
            $('#sessionDetails').html('<div style="text-align: center; color: var(--danger-color);">Gagal memuat detail sesi</div>');
        });
}

function deleteSession(id) {
    if (confirm('Apakah Anda yakin ingin menghapus sesi ini?')) {
        $.ajax({
            url: `/sessions/${id}`,
            method: 'DELETE',
            success: function(response) {
                if (response.success) {
                    $(`tr[data-session-id="${id}"]`).fadeOut(300, function() {
                        $(this).remove();
                    });
                    showAlert(response.message);
                } else {
                    showAlert('Gagal menghapus sesi', 'error');
                }
            },
            error: function() {
                showAlert('Gagal menghapus sesi', 'error');
            }
        });
    }
}

$('#sessionForm').on('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = $('#sessionSubmitBtn');
    showLoading(submitBtn);
    
    const url = isEditing ? `/sessions/${currentSessionId}` : '/sessions';
    const method = isEditing ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        data: $(this).serialize(),
        success: function(response) {
            hideLoading(submitBtn);
            
            if (response.success) {
                closeModal('sessionModal');
                showAlert(response.message);
                
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showAlert('Gagal menyimpan sesi', 'error');
            }
        },
        error: function(xhr) {
            hideLoading(submitBtn);
            
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                let errorMessage = 'Validasi gagal:\n';
                for (const field in errors) {
                    errorMessage += `• ${errors[field][0]}\n`;
                }
                showAlert(errorMessage, 'error');
            } else {
                showAlert('Gagal menyimpan sesi', 'error');
            }
        }
    });
});
</script>
@endpush