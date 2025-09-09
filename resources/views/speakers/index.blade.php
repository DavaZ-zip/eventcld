@extends('layouts.app')

@section('title', 'Manajemen Pembicara')

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title">Daftar Pembicara</h3>
            <button class="btn btn-primary" onclick="openCreateModal()">
                <i class="fas fa-plus"></i>
                Tambah Pembicara
            </button>
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Keahlian</th>
                        <th>Jumlah Sesi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="speakersTableBody">
                    @forelse($speakers as $speaker)
                    <tr data-speaker-id="{{ $speaker->id }}">
                        <td>
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary-color); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                @if($speaker->photo)
                                    <img src="{{ Storage::url($speaker->photo) }}" alt="{{ $speaker->name }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                @else
                                    {{ substr($speaker->name, 0, 1) }}
                                @endif
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $speaker->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">
                                {{ $speaker->phone ?? 'Tidak ada telepon' }}
                            </div>
                        </td>
                        <td>{{ $speaker->email }}</td>
                        <td>
                            <span class="badge badge-info">{{ $speaker->expertise ?? 'Belum ditentukan' }}</span>
                        </td>
                        <td>
                            <span class="badge badge-success">{{ $speaker->sessions_count ?? 0 }} sesi</span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.25rem;">
                                <button class="btn btn-warning btn-sm" onclick="editSpeaker({{ $speaker->id }})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-info btn-sm" onclick="viewSpeaker({{ $speaker->id }})" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteSpeaker({{ $speaker->id }})" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            <i class="fas fa-microphone-slash" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                            <div>Belum ada pembicara</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($speakers->hasPages())
    <div class="card-body">
        {{ $speakers->links() }}
    </div>
    @endif
</div>

<!-- Create/Edit Speaker Modal -->
<div id="speakerModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="speakerModalTitle">Tambah Pembicara Baru</h3>
            <button class="modal-close" onclick="closeModal('speakerModal')">&times;</button>
        </div>
        <form id="speakerForm" enctype="multipart/form-data">
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
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="expertise">Bidang Keahlian</label>
                        <input type="text" class="form-control" id="expertise" name="expertise" placeholder="e.g. AI, Marketing, Technology">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="bio">Biografi *</label>
                    <textarea class="form-control" id="bio" name="bio" rows="4" required placeholder="Ceritakan tentang latar belakang pembicara..."></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="photo">Foto Profil</label>
                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                    <small style="color: var(--text-muted); font-size: 0.75rem;">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Media Sosial</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                        <input type="text" class="form-control" id="linkedin" name="social_media[linkedin]" placeholder="LinkedIn Username">
                        <input type="text" class="form-control" id="twitter" name="social_media[twitter]" placeholder="Twitter Handle">
                        <input type="text" class="form-control" id="instagram" name="social_media[instagram]" placeholder="Instagram Username">
                        <input type="text" class="form-control" id="website" name="social_media[website]" placeholder="Website URL">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('speakerModal')">Batal</button>
                <button type="submit" class="btn btn-primary" id="speakerSubmitBtn">
                    <i class="fas fa-save"></i>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Speaker Modal -->
<div id="viewSpeakerModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Detail Pembicara</h3>
            <button class="modal-close" onclick="closeModal('viewSpeakerModal')">&times;</button>
        </div>
        <div class="modal-body" id="speakerDetails">
            <!-- Content will be loaded dynamically -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('viewSpeakerModal')">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentSpeakerId = null;
let isEditing = false;

// Open create modal
function openCreateModal() {
    isEditing = false;
    currentSpeakerId = null;
    $('#speakerModalTitle').text('Tambah Pembicara Baru');
    $('#speakerForm')[0].reset();
    openModal('speakerModal');
}

// Edit speaker
function editSpeaker(id) {
    isEditing = true;
    currentSpeakerId = id;
    $('#speakerModalTitle').text('Edit Pembicara');
    
    // Show loading in form
    showLoading($('#speakerSubmitBtn'));
    
    $.get(`/speakers/${id}`)
        .done(function(response) {
            $('#name').val(response.name);
            $('#email').val(response.email);
            $('#phone').val(response.phone || '');
            $('#bio').val(response.bio);
            $('#expertise').val(response.expertise || '');
            
            // Fill social media fields
            if (response.social_media) {
                $('#linkedin').val(response.social_media.linkedin || '');
                $('#twitter').val(response.social_media.twitter || '');
                $('#instagram').val(response.social_media.instagram || '');
                $('#website').val(response.social_media.website || '');
            }
            
            hideLoading($('#speakerSubmitBtn'));
            openModal('speakerModal');
        })
        .fail(function() {
            hideLoading($('#speakerSubmitBtn'));
            showAlert('Gagal memuat data pembicara', 'error');
        });
}

// View speaker details
function viewSpeaker(id) {
    $('#speakerDetails').html('<div class="loading"><div class="spinner"></div> Memuat...</div>');
    openModal('viewSpeakerModal');
    
    $.get(`/speakers/${id}`)
        .done(function(speaker) {
            const html = `
                <div style="display: flex; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--primary-color); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 2rem; flex-shrink: 0;">
                        ${speaker.photo ? `<img src="/storage/${speaker.photo}" alt="${speaker.name}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">` : speaker.name.charAt(0)}
                    </div>
                    <div style="flex: 1;">
                        <h4 style="margin-bottom: 0.5rem;">${speaker.name}</h4>
                        <div style="color: var(--text-muted); margin-bottom: 0.5rem;">
                            <i class="fas fa-envelope"></i> ${speaker.email}
                        </div>
                        ${speaker.phone ? `<div style="color: var(--text-muted); margin-bottom: 0.5rem;"><i class="fas fa-phone"></i> ${speaker.phone}</div>` : ''}
                        ${speaker.expertise ? `<span class="badge badge-info">${speaker.expertise}</span>` : ''}
                    </div>
                </div>
                
                <div style="margin-bottom: 1.5rem;">
                    <strong>Biografi:</strong>
                    <p style="margin-top: 0.5rem; color: var(--text-muted); line-height: 1.6;">${speaker.bio}</p>
                </div>
                
                ${speaker.social_media && Object.keys(speaker.social_media).length > 0 ? `
                <div style="margin-bottom: 1.5rem;">
                    <strong>Media Sosial:</strong>
                    <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem; flex-wrap: wrap;">
                        ${speaker.social_media.linkedin ? `<a href="https://linkedin.com/in/${speaker.social_media.linkedin}" target="_blank" class="btn btn-primary btn-sm"><i class="fab fa-linkedin"></i> LinkedIn</a>` : ''}
                        ${speaker.social_media.twitter ? `<a href="https://twitter.com/${speaker.social_media.twitter}" target="_blank" class="btn btn-info btn-sm"><i class="fab fa-twitter"></i> Twitter</a>` : ''}
                        ${speaker.social_media.instagram ? `<a href="https://instagram.com/${speaker.social_media.instagram}" target="_blank" class="btn btn-danger btn-sm"><i class="fab fa-instagram"></i> Instagram</a>` : ''}
                        ${speaker.social_media.website ? `<a href="${speaker.social_media.website}" target="_blank" class="btn btn-success btn-sm"><i class="fas fa-globe"></i> Website</a>` : ''}
                    </div>
                </div>
                ` : ''}
                
                ${speaker.sessions && speaker.sessions.length > 0 ? `
                <div style="margin-bottom: 1.5rem;">
                    <strong>Sesi yang Ditangani (${speaker.sessions.length}):</strong>
                    <div style="margin-top: 0.5rem;">
                        ${speaker.sessions.map(session => `
                            <div style="padding: 0.75rem; background: var(--light-color); border-radius: 8px; margin-bottom: 0.5rem; border-left: 3px solid var(--primary-color);">
                                <div style="font-weight: 600; margin-bottom: 0.25rem;">${session.title}</div>
                                <div style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.25rem;">${session.event ? session.event.title : 'Acara tidak ditemukan'}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">
                                    <i class="fas fa-clock"></i> ${new Date(session.start_time).toLocaleString('id-ID')} • 
                                    <i class="fas fa-map-marker-alt"></i> ${session.location}
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
                ` : `
                <div style="text-align: center; padding: 1rem; color: var(--text-muted);">
                    <i class="fas fa-calendar-times" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div>Belum ada sesi yang ditangani</div>
                </div>
                `}
                
                <div style="display: flex; gap: 0.5rem; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                    <button class="btn btn-primary btn-sm" onclick="closeModal('viewSpeakerModal'); editSpeaker(${speaker.id})">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </div>
            `;
            
            $('#speakerDetails').html(html);
        })
        .fail(function() {
            $('#speakerDetails').html('<div style="text-align: center; color: var(--danger-color);">Gagal memuat detail pembicara</div>');
        });
}

// Delete speaker
function deleteSpeaker(id) {
    if (confirm('Apakah Anda yakin ingin menghapus pembicara ini?')) {
        $.ajax({
            url: `/speakers/${id}`,
            method: 'DELETE',
            success: function(response) {
                if (response.success) {
                    $(`tr[data-speaker-id="${id}"]`).fadeOut(300, function() {
                        $(this).remove();
                    });
                    showAlert(response.message);
                } else {
                    showAlert('Gagal menghapus pembicara', 'error');
                }
            },
            error: function() {
                showAlert('Gagal menghapus pembicara', 'error');
            }
        });
    }
}

// Handle form submission
$('#speakerForm').on('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = $('#speakerSubmitBtn');
    showLoading(submitBtn);
    
    const formData = new FormData(this);
    const url = isEditing ? `/speakers/${currentSpeakerId}` : '/speakers';
    
    if (isEditing) {
        formData.append('_method', 'PUT');
    }
    
    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            hideLoading(submitBtn);
            
            if (response.success) {
                closeModal('speakerModal');
                showAlert(response.message);
                
                // Reload page to show updated data
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showAlert('Gagal menyimpan pembicara', 'error');
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
                showAlert('Gagal menyimpan pembicara', 'error');
            }
        }
    });
});
</script>
@endpush