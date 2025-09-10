@extends('layouts.app')

@section('title', 'Manajemen Peserta')

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title">Daftar Peserta</h3>
            <button class="btn btn-primary" onclick="openCreateModal()">
                <i class="fas fa-plus"></i>
                Tambah Peserta
            </button>
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Organisasi</th>
                        <th>Posisi</th>
                        <th>Acara Diikuti</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="participantsTableBody">
                    @forelse($participants as $participant)
                    <tr data-participant-id="{{ $participant->id }}">
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--primary-color); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                    {{ substr($participant->name, 0, 1) }}
                                </div>
                                <div>
                                    <div style="font-weight: 600;">{{ $participant->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $participant->email }}</td>
                        <td>{{ $participant->phone ?? '-' }}</td>
                        <td>{{ $participant->organization ?? '-' }}</td>
                        <td>{{ $participant->position ?? '-' }}</td>
                        <td>
                            <span class="badge badge-info">{{ $participant->events_count ?? 0 }} acara</span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.25rem;">
                                <button class="btn btn-warning btn-sm" onclick="editParticipant({{ $participant->id }})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-info btn-sm" onclick="viewParticipant({{ $participant->id }})" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteParticipant({{ $participant->id }})" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            <i class="fas fa-users" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                            <div>Belum ada peserta</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($participants->hasPages())
    <div class="card-body">
        {{ $participants->links() }}
    </div>
    @endif
</div>

<!-- Create/Edit Participant Modal -->
<div id="participantModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="participantModalTitle">Tambah Peserta Baru</h3>
            <button class="modal-close" onclick="closeModal('participantModal')">&times;</button>
        </div>
        <form id="participantForm">
            <div class="modal-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label" for="name">Nama Lengkap *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="email">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label" for="phone">Telepon</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="08123456789">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="organization">Organisasi/Perusahaan</label>
                        <input type="text" class="form-control" id="organization" name="organization" placeholder="Nama perusahaan atau organisasi">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="position">Posisi/Jabatan</label>
                    <input type="text" class="form-control" id="position" name="position" placeholder="e.g. Manager, Developer, Student">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('participantModal')">Batal</button>
                <button type="submit" class="btn btn-primary" id="participantSubmitBtn">
                    <i class="fas fa-save"></i>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Participant Modal -->
<div id="viewParticipantModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Detail Peserta</h3>
            <button class="modal-close" onclick="closeModal('viewParticipantModal')">&times;</button>
        </div>
        <div class="modal-body" id="participantDetails">
            <!-- Content will be loaded dynamically -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('viewParticipantModal')">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentParticipantId = null;
let isEditing = false;

// Open create modal
function openCreateModal() {
    isEditing = false;
    currentParticipantId = null;
    $('#participantModalTitle').text('Tambah Peserta Baru');
    $('#participantForm')[0].reset();
    openModal('participantModal');
}

// Edit participant
function editParticipant(id) {
    isEditing = true;
    currentParticipantId = id;
    $('#participantModalTitle').text('Edit Peserta');
    
    // Show loading in form
    showLoading($('#participantSubmitBtn'));
    
    $.get(`/participants/${id}`)
        .done(function(response) {
            $('#name').val(response.name);
            $('#email').val(response.email);
            $('#phone').val(response.phone || '');
            $('#organization').val(response.organization || '');
            $('#position').val(response.position || '');
            
            hideLoading($('#participantSubmitBtn'));
            openModal('participantModal');
        })
        .fail(function() {
            hideLoading($('#participantSubmitBtn'));
            showAlert('Gagal memuat data peserta', 'error');
        });
}

// View participant details
function viewParticipant(id) {
    $('#participantDetails').html('<div class="loading"><div class="spinner"></div> Memuat...</div>');
    openModal('viewParticipantModal');
    
    $.get(`/participants/${id}`)
        .done(function(participant) {
            const html = `
                <div style="display: flex; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--primary-color); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 2rem; flex-shrink: 0;">
                        ${participant.name.charAt(0)}
                    </div>
                    <div style="flex: 1;">
                        <h4 style="margin-bottom: 0.5rem;">${participant.name}</h4>
                        <div style="color: var(--text-muted); margin-bottom: 0.5rem;">
                            <i class="fas fa-envelope"></i> ${participant.email}
                        </div>
                        ${participant.phone ? `<div style="color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fas fa-phone"></i> ${participant.phone}</div>` : ''}
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            ${participant.organization ? `<span class="badge badge-info">${participant.organization}</span>` : ''}
                            ${participant.position ? `<span class="badge badge-success">${participant.position}</span>` : ''}
                        </div>
                    </div>
                </div>
                
                <div style="margin-bottom: 1.5rem;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        <div style="text-align: center; padding: 1rem; background: var(--light-color); border-radius: 8px;">
                            <div style="font-size: 1.5rem; font-weight: 700; color: var(--primary-color);">${participant.events ? participant.events.length : 0}</div>
                            <div style="color: var(--text-muted); font-size: 0.875rem;">Acara Diikuti</div>
                        </div>
                        <div style="text-align: center; padding: 1rem; background: var(--light-color); border-radius: 8px;">
                            <div style="font-size: 1.5rem; font-weight: 700; color: var(--success-color);">${participant.sessions ? participant.sessions.length : 0}</div>
                            <div style="color: var(--text-muted); font-size: 0.875rem;">Sesi Diikuti</div>
                        </div>
                    </div>
                </div>
                
                ${participant.events && participant.events.length > 0 ? `
                <div style="margin-bottom: 1.5rem;">
                    <strong>Acara yang Diikuti (${participant.events.length}):</strong>
                    <div style="margin-top: 0.5rem;">
                        ${participant.events.map(event => {
                            const statusColors = {
                                'draft': 'badge-warning',
                                'published': 'badge-success',
                                'ongoing': 'badge-info',
                                'completed': 'badge-success',
                                'cancelled': 'badge-danger'
                            };
                            const statusLabels = {
                                'draft': 'Draft',
                                'published': 'Dipublikasi',
                                'ongoing': 'Berlangsung',
                                'completed': 'Selesai',
                                'cancelled': 'Dibatalkan'
                            };
                            return `
                            <div style="padding: 0.75rem; background: white; border: 1px solid var(--border-color); border-radius: 8px; margin-bottom: 0.5rem;">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                                    <div style="font-weight: 600;">${event.title}</div>
                                    <span class="badge ${statusColors[event.status] || 'badge-info'}">${statusLabels[event.status] || event.status}</span>
                                </div>
                                <div style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.25rem;">
                                    <i class="fas fa-calendar"></i> ${new Date(event.start_date).toLocaleDateString('id-ID')} • 
                                    <i class="fas fa-map-marker-alt"></i> ${event.location}
                                </div>
                                ${event.pivot && event.pivot.status ? `
                                <div style="font-size: 0.75rem;">
                                    Status: <span class="badge badge-info">${event.pivot.status}</span>
                                    ${event.pivot.registration_date ? ` • Daftar: ${new Date(event.pivot.registration_date).toLocaleDateString('id-ID')}` : ''}
                                </div>
                                ` : ''}
                            </div>
                        `;
                        }).join('')}
                    </div>
                </div>
                ` : `
                <div style="text-align: center; padding: 1.5rem; color: var(--text-muted);">
                    <i class="fas fa-calendar-times" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div>Belum mengikuti acara apapun</div>
                </div>
                `}
                
                <div style="display: flex; gap: 0.5rem; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                    <button class="btn btn-primary btn-sm" onclick="closeModal('viewParticipantModal'); editParticipant(${participant.id})">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </div>
            `;
            
            $('#participantDetails').html(html);
        })
        .fail(function() {
            $('#participantDetails').html('<div style="text-align: center; color: var(--danger-color);">Gagal memuat detail peserta</div>');
        });
}

// Delete participant
function deleteParticipant(id) {
    if (confirm('Apakah Anda yakin ingin menghapus peserta ini?')) {
        $.ajax({
            url: `/participants/${id}`,
            method: 'DELETE',
            success: function(response) {
                if (response.success) {
                    $(`tr[data-participant-id="${id}"]`).fadeOut(300, function() {
                        $(this).remove();
                    });
                    showAlert(response.message);
                } else {
                    showAlert('Gagal menghapus peserta', 'error');
                }
            },
            error: function() {
                showAlert('Gagal menghapus peserta', 'error');
            }
        });
    }
}

// Handle form submission
$('#participantForm').on('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = $('#participantSubmitBtn');
    showLoading(submitBtn);
    
    const url = isEditing ? `/participants/${currentParticipantId}` : '/participants';
    const method = isEditing ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        data: $(this).serialize(),
        success: function(response) {
            hideLoading(submitBtn);
            
            if (response.success) {
                closeModal('participantModal');
                showAlert(response.message);
                
                // Reload page to show updated data
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showAlert('Gagal menyimpan peserta', 'error');
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
                showAlert('Gagal menyimpan peserta', 'error');
            }
        }
    });
});
</script>
@endpush