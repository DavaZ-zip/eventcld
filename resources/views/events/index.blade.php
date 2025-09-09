@extends('layouts.app')

@section('title', 'Manajemen Acara')

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title">Daftar Acara</h3>
            <button class="btn btn-primary" onclick="openCreateModal()">
                <i class="fas fa-plus"></i>
                Tambah Acara
            </button>
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Acara</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Peserta</th>
                        <th>Status</th>
                        <th>Dibuat oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="eventsTableBody">
                    @forelse($events as $event)
                    <tr data-event-id="{{ $event->id }}">
                        <td>
                            <div style="font-weight: 600;">{{ $event->title }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">
                                {{ Str::limit($event->description, 50) }}
                            </div>
                        </td>
                        <td>
                            <div>{{ $event->start_date->format('d M Y') }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">
                                {{ $event->start_date->format('H:i') }} - {{ $event->end_date->format('H:i') }}
                            </div>
                        </td>
                        <td>{{ $event->location }}</td>
                        <td>
                            <span class="badge badge-info">
                                {{ $event->participants->count() }}/{{ $event->max_participants }}
                            </span>
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'draft' => 'badge-warning',
                                    'published' => 'badge-success',
                                    'ongoing' => 'badge-info',
                                    'completed' => 'badge-success',
                                    'cancelled' => 'badge-danger'
                                ];
                                $statusLabels = [
                                    'draft' => 'Draft',
                                    'published' => 'Dipublikasi',
                                    'ongoing' => 'Berlangsung',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Dibatalkan'
                                ];
                            @endphp
                            <span class="badge {{ $statusColors[$event->status] ?? 'badge-info' }}">
                                {{ $statusLabels[$event->status] ?? ucfirst($event->status) }}
                            </span>
                        </td>
                        <td>{{ $event->creator->name }}</td>
                        <td>
                            <div style="display: flex; gap: 0.25rem;">
                                <button class="btn btn-warning btn-sm" onclick="editEvent({{ $event->id }})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-info btn-sm" onclick="viewEvent({{ $event->id }})" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteEvent({{ $event->id }})" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            <i class="fas fa-calendar-times" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                            <div>Belum ada acara</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($events->hasPages())
    <div class="card-body">
        {{ $events->links() }}
    </div>
    @endif
</div>

<!-- Create/Edit Event Modal -->
<div id="eventModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="eventModalTitle">Tambah Acara Baru</h3>
            <button class="modal-close" onclick="closeModal('eventModal')">&times;</button>
        </div>
        <form id="eventForm">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="title">Nama Acara *</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="description">Deskripsi *</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label" for="start_date">Tanggal Mulai *</label>
                        <input type="datetime-local" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="end_date">Tanggal Selesai *</label>
                        <input type="datetime-local" class="form-control" id="end_date" name="end_date" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="location">Lokasi *</label>
                    <input type="text" class="form-control" id="location" name="location" required>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label" for="max_participants">Maksimal Peserta *</label>
                        <input type="number" class="form-control" id="max_participants" name="max_participants" min="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="status">Status *</label>
                        <select class="form-control form-select" id="status" name="status" required>
                            <option value="draft">Draft</option>
                            <option value="published">Dipublikasi</option>
                            <option value="ongoing">Berlangsung</option>
                            <option value="completed">Selesai</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('eventModal')">Batal</button>
                <button type="submit" class="btn btn-primary" id="eventSubmitBtn">
                    <i class="fas fa-save"></i>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Event Modal -->
<div id="viewEventModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Detail Acara</h3>
            <button class="modal-close" onclick="closeModal('viewEventModal')">&times;</button>
        </div>
        <div class="modal-body" id="eventDetails">
            <!-- Content will be loaded dynamically -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('viewEventModal')">Tutup</button>
        </div>
    </div>
</div>

<!-- Register Participant Modal -->
<div id="registerModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Daftarkan Peserta</h3>
            <button class="modal-close" onclick="closeModal('registerModal')">&times;</button>
        </div>
        <form id="registerForm">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="participant_id">Pilih Peserta *</label>
                    <select class="form-control form-select" id="participant_id" name="participant_id" required>
                        <option value="">-- Pilih Peserta --</option>
                        <!-- Options will be loaded dynamically -->
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('registerModal')">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i>
                    Daftarkan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentEventId = null;
let isEditing = false;

// Open create modal
function openCreateModal() {
    isEditing = false;
    currentEventId = null;
    $('#eventModalTitle').text('Tambah Acara Baru');
    $('#eventForm')[0].reset();
    openModal('eventModal');
}

// Edit event
function editEvent(id) {
    isEditing = true;
    currentEventId = id;
    $('#eventModalTitle').text('Edit Acara');
    
    // Show loading in form
    showLoading($('#eventSubmitBtn'));
    
    $.get(`/events/${id}`)
        .done(function(response) {
            $('#title').val(response.title);
            $('#description').val(response.description);
            $('#start_date').val(response.start_date.replace(' ', 'T').substring(0, 16));
            $('#end_date').val(response.end_date.replace(' ', 'T').substring(0, 16));
            $('#location').val(response.location);
            $('#max_participants').val(response.max_participants);
            $('#status').val(response.status);
            
            hideLoading($('#eventSubmitBtn'));
            openModal('eventModal');
        })
        .fail(function() {
            hideLoading($('#eventSubmitBtn'));
            showAlert('Gagal memuat data acara', 'error');
        });
}

// View event details
function viewEvent(id) {
    $('#eventDetails').html('<div class="loading"><div class="spinner"></div> Memuat...</div>');
    openModal('viewEventModal');
    
    $.get(`/events/${id}`)
        .done(function(event) {
            const statusLabels = {
                'draft': 'Draft',
                'published': 'Dipublikasi',
                'ongoing': 'Berlangsung',
                'completed': 'Selesai',
                'cancelled': 'Dibatalkan'
            };
            
            const statusColors = {
                'draft': 'badge-warning',
                'published': 'badge-success',
                'ongoing': 'badge-info',
                'completed': 'badge-success',
                'cancelled': 'badge-danger'
            };
            
            const startDate = new Date(event.start_date);
            const endDate = new Date(event.end_date);
            
            const html = `
                <div style="margin-bottom: 1.5rem;">
                    <h4 style="margin-bottom: 0.5rem;">${event.title}</h4>
                    <span class="badge ${statusColors[event.status] || 'badge-info'}">${statusLabels[event.status] || event.status}</span>
                </div>
                
                <div style="margin-bottom: 1rem;">
                    <strong>Deskripsi:</strong>
                    <p style="margin-top: 0.5rem; color: var(--text-muted);">${event.description}</p>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <strong>Tanggal Mulai:</strong>
                        <div>${startDate.toLocaleDateString('id-ID', { 
                            day: 'numeric', 
                            month: 'long', 
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        })}</div>
                    </div>
                    <div>
                        <strong>Tanggal Selesai:</strong>
                        <div>${endDate.toLocaleDateString('id-ID', { 
                            day: 'numeric', 
                            month: 'long', 
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        })}</div>
                    </div>
                </div>
                
                <div style="margin-bottom: 1rem;">
                    <strong>Lokasi:</strong> ${event.location}
                </div>
                
                <div style="margin-bottom: 1rem;">
                    <strong>Kapasitas:</strong> ${event.participants ? event.participants.length : 0}/${event.max_participants} peserta
                </div>
                
                ${event.sessions && event.sessions.length > 0 ? `
                <div style="margin-bottom: 1rem;">
                    <strong>Sesi (${event.sessions.length}):</strong>
                    <div style="margin-top: 0.5rem;">
                        ${event.sessions.map(session => `
                            <div style="padding: 0.5rem; background: var(--light-color); border-radius: 6px; margin-bottom: 0.5rem;">
                                <div style="font-weight: 600;">${session.title}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">
                                    ${session.speaker ? session.speaker.name : 'Speaker belum ditentukan'} • ${session.location}
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
                ` : ''}
                
                <div style="display: flex; gap: 0.5rem; margin-top: 1.5rem;">
                    <button class="btn btn-primary btn-sm" onclick="closeModal('viewEventModal'); editEvent(${event.id})">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-success btn-sm" onclick="openRegisterModal(${event.id})">
                        <i class="fas fa-user-plus"></i> Daftarkan Peserta
                    </button>
                </div>
            `;
            
            $('#eventDetails').html(html);
        })
        .fail(function() {
            $('#eventDetails').html('<div style="text-align: center; color: var(--danger-color);">Gagal memuat detail acara</div>');
        });
}

// Delete event
function deleteEvent(id) {
    if (confirm('Apakah Anda yakin ingin menghapus acara ini?')) {
        $.ajax({
            url: `/events/${id}`,
            method: 'DELETE',
            success: function(response) {
                if (response.success) {
                    $(`tr[data-event-id="${id}"]`).fadeOut(300, function() {
                        $(this).remove();
                    });
                    showAlert(response.message);
                } else {
                    showAlert('Gagal menghapus acara', 'error');
                }
            },
            error: function() {
                showAlert('Gagal menghapus acara', 'error');
            }
        });
    }
}

// Handle form submission
$('#eventForm').on('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = $('#eventSubmitBtn');
    showLoading(submitBtn);
    
    const url = isEditing ? `/events/${currentEventId}` : '/events';
    const method = isEditing ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        data: $(this).serialize(),
        success: function(response) {
            hideLoading(submitBtn);
            
            if (response.success) {
                closeModal('eventModal');
                showAlert(response.message);
                
                // Reload page to show updated data
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showAlert('Gagal menyimpan acara', 'error');
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
                showAlert('Gagal menyimpan acara', 'error');
            }
        }
    });
});

// Open register participant modal
function openRegisterModal(eventId) {
    currentEventId = eventId;
    closeModal('viewEventModal');
    
    // Load participants
    $('#participant_id').html('<option value="">Memuat...</option>');
    
    $.get('/participants')
        .done(function(response) {
            let options = '<option value="">-- Pilih Peserta --</option>';
            // Assuming response has participants data
            if (response.data) {
                response.data.forEach(participant => {
                    options += `<option value="${participant.id}">${participant.name} (${participant.email})</option>`;
                });
            }
            $('#participant_id').html(options);
        })
        .fail(function() {
            $('#participant_id').html('<option value="">Gagal memuat peserta</option>');
        });
    
    openModal('registerModal');
}

// Handle participant registration
$('#registerForm').on('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = $(this).find('button[type="submit"]');
    showLoading(submitBtn);
    
    $.ajax({
        url: `/events/${currentEventId}/register`,
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            hideLoading(submitBtn);
            
            if (response.success) {
                closeModal('registerModal');
                showAlert(response.message);
            } else {
                showAlert(response.message || 'Gagal mendaftarkan peserta', 'error');
            }
        },
        error: function(xhr) {
            hideLoading(submitBtn);
            
            if (xhr.status === 422 && xhr.responseJSON.message) {
                showAlert(xhr.responseJSON.message, 'error');
            } else {
                showAlert('Gagal mendaftarkan peserta', 'error');
            }
        }
    });
});
</script>
@endpush