@extends('layouts.app')

@section('title', 'Manajemen Sponsor')

@section('content')
<div class="card">
    <div class="card-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title">Daftar Sponsor</h3>
            <button class="btn btn-primary" onclick="openCreateModal()">
                <i class="fas fa-plus"></i>
                Tambah Sponsor
            </button>
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Nama Sponsor</th>
                        <th>Kontak</th>
                        <th>Level</th>
                        <th>Kontribusi</th>
                        <th>Acara</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sponsors as $sponsor)
                    <tr data-sponsor-id="{{ $sponsor->id }}">
                        <td>
                            <div style="width: 40px; height: 40px; border-radius: 8px; background: var(--light-color); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center;">
                                @if($sponsor->logo)
                                    <img src="{{ Storage::url($sponsor->logo) }}" alt="{{ $sponsor->name }}" style="width: 100%; height: 100%; object-fit: contain; border-radius: 6px;">
                                @else
                                    <i class="fas fa-building" style="color: var(--text-muted);"></i>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600;">{{ $sponsor->name }}</div>
                            @if($sponsor->website)
                            <a href="{{ $sponsor->website }}" target="_blank" style="font-size: 0.75rem; color: var(--primary-color); text-decoration: none;">
                                <i class="fas fa-external-link-alt"></i> Website
                            </a>
                            @endif
                        </td>
                        <td>
                            <div style="font-size: 0.875rem;">{{ $sponsor->contact_person }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $sponsor->contact_email }}</div>
                        </td>
                        <td>
                            @php
                                $levelColors = [
                                    'platinum' => 'badge-info',
                                    'gold' => 'badge-warning',
                                    'silver' => 'badge-secondary',
                                    'bronze' => 'badge-danger'
                                ];
                                $levelLabels = [
                                    'platinum' => 'Platinum',
                                    'gold' => 'Gold',
                                    'silver' => 'Silver',
                                    'bronze' => 'Bronze'
                                ];
                            @endphp
                            <span class="badge {{ $levelColors[$sponsor->sponsorship_level] ?? 'badge-info' }}">
                                {{ $levelLabels[$sponsor->sponsorship_level] ?? ucfirst($sponsor->sponsorship_level) }}
                            </span>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: var(--success-color);">
                                Rp {{ number_format($sponsor->contribution_amount, 0, ',', '.') }}
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-success">{{ $sponsor->events_count ?? 0 }} acara</span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.25rem;">
                                <button class="btn btn-warning btn-sm" onclick="editSponsor({{ $sponsor->id }})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-info btn-sm" onclick="viewSponsor({{ $sponsor->id }})" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteSponsor({{ $sponsor->id }})" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            <i class="fas fa-handshake" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                            <div>Belum ada sponsor</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($sponsors->hasPages())
    <div class="card-body">
        {{ $sponsors->links() }}
    </div>
    @endif
</div>

<!-- Create/Edit Sponsor Modal -->
<div id="sponsorModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="sponsorModalTitle">Tambah Sponsor Baru</h3>
            <button class="modal-close" onclick="closeModal('sponsorModal')">&times;</button>
        </div>
        <form id="sponsorForm" enctype="multipart/form-data">
            <div class="modal-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label" for="name">Nama Sponsor *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="website">Website</label>
                        <input type="url" class="form-control" id="website" name="website" placeholder="https://example.com">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="description">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Deskripsi singkat tentang sponsor"></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="logo">Logo</label>
                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                    <small style="color: var(--text-muted); font-size: 0.75rem;">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label" for="contact_person">Nama Kontak *</label>
                        <input type="text" class="form-control" id="contact_person" name="contact_person" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="contact_email">Email Kontak *</label>
                        <input type="email" class="form-control" id="contact_email" name="contact_email" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="contact_phone">Telepon Kontak</label>
                        <input type="text" class="form-control" id="contact_phone" name="contact_phone">
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label" for="contribution_amount">Jumlah Kontribusi *</label>
                        <input type="number" class="form-control" id="contribution_amount" name="contribution_amount" min="0" step="1000" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="sponsorship_level">Level Sponsorship *</label>
                        <select class="form-control form-select" id="sponsorship_level" name="sponsorship_level" required>
                            <option value="">-- Pilih Level --</option>
                            <option value="platinum">Platinum</option>
                            <option value="gold">Gold</option>
                            <option value="silver">Silver</option>
                            <option value="bronze">Bronze</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('sponsorModal')">Batal</button>
                <button type="submit" class="btn btn-primary" id="sponsorSubmitBtn">
                    <i class="fas fa-save"></i>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Sponsor Modal -->
<div id="viewSponsorModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Detail Sponsor</h3>
            <button class="modal-close" onclick="closeModal('viewSponsorModal')">&times;</button>
        </div>
        <div class="modal-body" id="sponsorDetails">
            <!-- Content will be loaded dynamically -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('viewSponsorModal')">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentSponsorId = null;
let isEditing = false;

function openCreateModal() {
    isEditing = false;
    currentSponsorId = null;
    $('#sponsorModalTitle').text('Tambah Sponsor Baru');
    $('#sponsorForm')[0].reset();
    openModal('sponsorModal');
}

function editSponsor(id) {
    isEditing = true;
    currentSponsorId = id;
    $('#sponsorModalTitle').text('Edit Sponsor');
    
    showLoading($('#sponsorSubmitBtn'));
    
    $.get(`/sponsors/${id}`)
        .done(function(response) {
            $('#name').val(response.name);
            $('#website').val(response.website || '');
            $('#description').val(response.description || '');
            $('#contact_person').val(response.contact_person);
            $('#contact_email').val(response.contact_email);
            $('#contact_phone').val(response.contact_phone || '');
            $('#contribution_amount').val(response.contribution_amount);
            $('#sponsorship_level').val(response.sponsorship_level);
            
            hideLoading($('#sponsorSubmitBtn'));
            openModal('sponsorModal');
        })
        .fail(function() {
            hideLoading($('#sponsorSubmitBtn'));
            showAlert('Gagal memuat data sponsor', 'error');
        });
}

function viewSponsor(id) {
    $('#sponsorDetails').html('<div class="loading"><div class="spinner"></div> Memuat...</div>');
    openModal('viewSponsorModal');
    
    $.get(`/sponsors/${id}`)
        .done(function(sponsor) {
            const levelColors = {
                'platinum': 'badge-info',
                'gold': 'badge-warning',
                'silver': 'badge-secondary',
                'bronze': 'badge-danger'
            };
            const levelLabels = {
                'platinum': 'Platinum',
                'gold': 'Gold',
                'silver': 'Silver',
                'bronze': 'Bronze'
            };
            
            const html = `
                <div style="display: flex; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div style="width: 80px; height: 80px; border-radius: 12px; background: var(--light-color); border: 2px solid var(--border-color); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        ${sponsor.logo ? `<img src="/storage/${sponsor.logo}" alt="${sponsor.name}" style="width: 100%; height: 100%; object-fit: contain; border-radius: 10px;">` : `<i class="fas fa-building" style="font-size: 2rem; color: var(--text-muted);"></i>`}
                    </div>
                    <div style="flex: 1;">
                        <h4 style="margin-bottom: 0.5rem;">${sponsor.name}</h4>
                        <span class="badge ${levelColors[sponsor.sponsorship_level] || 'badge-info'}">${levelLabels[sponsor.sponsorship_level] || sponsor.sponsorship_level}</span>
                        ${sponsor.website ? `<div style="margin-top: 0.5rem;"><a href="${sponsor.website}" target="_blank" class="btn btn-primary btn-sm"><i class="fas fa-external-link-alt"></i> Kunjungi Website</a></div>` : ''}
                    </div>
                </div>
                
                ${sponsor.description ? `
                <div style="margin-bottom: 1.5rem;">
                    <strong>Deskripsi:</strong>
                    <p style="margin-top: 0.5rem; color: var(--text-muted); line-height: 1.6;">${sponsor.description}</p>
                </div>
                ` : ''}
                
                <div style="margin-bottom: 1.5rem;">
                    <strong>Informasi Kontak:</strong>
                    <div style="background: var(--light-color); padding: 1rem; border-radius: 8px; margin-top: 0.5rem;">
                        <div style="margin-bottom: 0.5rem;">
                            <i class="fas fa-user" style="width: 16px;"></i> <strong>Nama:</strong> ${sponsor.contact_person}
                        </div>
                        <div style="margin-bottom: 0.5rem;">
                            <i class="fas fa-envelope" style="width: 16px;"></i> <strong>Email:</strong> ${sponsor.contact_email}
                        </div>
                        ${sponsor.contact_phone ? `<div><i class="fas fa-phone" style="width: 16px;"></i> <strong>Telepon:</strong> ${sponsor.contact_phone}</div>` : ''}
                    </div>
                </div>
                
                <div style="margin-bottom: 1.5rem;">
                    <strong>Kontribusi:</strong>
                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--success-color); margin-top: 0.5rem;">
                        Rp ${new Intl.NumberFormat('id-ID').format(sponsor.contribution_amount)}
                    </div>
                </div>
                
                ${sponsor.events && sponsor.events.length > 0 ? `
                <div style="margin-bottom: 1.5rem;">
                    <strong>Acara yang Disponsori (${sponsor.events.length}):</strong>
                    <div style="margin-top: 0.5rem;">
                        ${sponsor.events.map(event => `
                            <div style="padding: 0.75rem; background: white; border: 1px solid var(--border-color); border-radius: 8px; margin-bottom: 0.5rem;">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                                    <div style="font-weight: 600;">${event.title}</div>
                                    <span class="badge ${levelColors[event.pivot.sponsorship_level] || 'badge-info'}">${levelLabels[event.pivot.sponsorship_level] || event.pivot.sponsorship_level}</span>
                                </div>
                                <div style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.25rem;">
                                    <i class="fas fa-calendar"></i> ${new Date(event.start_date).toLocaleDateString('id-ID')} • 
                                    <i class="fas fa-map-marker-alt"></i> ${event.location}
                                </div>
                                <div style="font-size: 0.875rem; font-weight: 600; color: var(--success-color);">
                                    Kontribusi: Rp ${new Intl.NumberFormat('id-ID').format(event.pivot.contribution_amount)}
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
                ` : `
                <div style="text-align: center; padding: 1.5rem; color: var(--text-muted);">
                    <i class="fas fa-calendar-times" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                    <div>Belum mensponsori acara apapun</div>
                </div>
                `}
                
                <div style="display: flex; gap: 0.5rem; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                    <button class="btn btn-primary btn-sm" onclick="closeModal('viewSponsorModal'); editSponsor(${sponsor.id})">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </div>
            `;
            
            $('#sponsorDetails').html(html);
        })
        .fail(function() {
            $('#sponsorDetails').html('<div style="text-align: center; color: var(--danger-color);">Gagal memuat detail sponsor</div>');
        });
}

function deleteSponsor(id) {
    if (confirm('Apakah Anda yakin ingin menghapus sponsor ini?')) {
        $.ajax({
            url: `/sponsors/${id}`,
            method: 'DELETE',
            success: function(response) {
                if (response.success) {
                    $(`tr[data-sponsor-id="${id}"]`).fadeOut(300, function() {
                        $(this).remove();
                    });
                    showAlert(response.message);
                } else {
                    showAlert('Gagal menghapus sponsor', 'error');
                }
            },
            error: function() {
                showAlert('Gagal menghapus sponsor', 'error');
            }
        });
    }
}

$('#sponsorForm').on('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = $('#sponsorSubmitBtn');
    showLoading(submitBtn);
    
    const formData = new FormData(this);
    const url = isEditing ? `/sponsors/${currentSponsorId}` : '/sponsors';
    
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
                closeModal('sponsorModal');
                showAlert(response.message);
                
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showAlert('Gagal menyimpan sponsor', 'error');
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
                showAlert('Gagal menyimpan sponsor', 'error');
            }
        }
    });
});
</script>
@endpush