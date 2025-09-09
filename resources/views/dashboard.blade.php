@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-calendar"></i>
        </div>
        <div class="stat-value">{{ $stats['total_events'] }}</div>
        <div class="stat-label">Total Acara</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--success-color);">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-value">{{ $stats['active_events'] }}</div>
        <div class="stat-label">Acara Aktif</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--warning-color);">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-value">{{ $stats['total_participants'] }}</div>
        <div class="stat-label">Total Peserta</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--danger-color);">
            <i class="fas fa-microphone"></i>
        </div>
        <div class="stat-value">{{ $stats['total_speakers'] }}</div>
        <div class="stat-label">Total Pembicara</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #8b5cf6;">
            <i class="fas fa-handshake"></i>
        </div>
        <div class="stat-value">{{ $stats['total_sponsors'] }}</div>
        <div class="stat-label">Total Sponsor</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #06b6d4;">
            <i class="fas fa-calendar-plus"></i>
        </div>
        <div class="stat-value">{{ $stats['upcoming_events'] }}</div>
        <div class="stat-label">Acara Mendatang</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 2rem;">
    <!-- Recent Events -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Acara Terbaru</h3>
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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_events as $event)
                        <tr>
                            <td>
                                <div style="font-weight: 600;">{{ $event->title }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">
                                    oleh {{ $event->creator->name }}
                                </div>
                            </td>
                            <td>
                                <div>{{ $event->start_date->format('d M Y') }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">
                                    {{ $event->start_date->format('H:i') }}
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
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                                <i class="fas fa-calendar-times" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                                <div>Belum ada acara</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Statistik Cepat</h3>
        </div>
        <div class="card-body">
            <div style="margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="font-size: 0.875rem;">Acara Bulan Ini</span>
                    <span style="font-weight: 600;">{{ $monthly_registrations[date('n')] ?? 0 }}</span>
                </div>
                <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px;">
                    <div style="width: {{ ($monthly_registrations[date('n')] ?? 0) > 0 ? min(($monthly_registrations[date('n')] / max(array_values($monthly_registrations) ?: [1])) * 100, 100) : 0 }}%; height: 100%; background: var(--primary-color); border-radius: 4px;"></div>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="font-size: 0.875rem;">Tingkat Kehadiran</span>
                    <span style="font-weight: 600;">85%</span>
                </div>
                <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px;">
                    <div style="width: 85%; height: 100%; background: var(--success-color); border-radius: 4px;"></div>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="font-size: 0.875rem;">Kapasitas Rata-rata</span>
                    <span style="font-weight: 600;">72%</span>
                </div>
                <div style="width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px;">
                    <div style="width: 72%; height: 100%; background: var(--warning-color); border-radius: 4px;"></div>
                </div>
            </div>

            <div style="text-align: center; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                <a href="{{ route('events.index') }}" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-plus"></i>
                    Buat Acara Baru
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Chart -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Registrasi Acara per Bulan</h3>
    </div>
    <div class="card-body">
        <canvas id="monthlyChart" width="400" height="100"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
$(document).ready(function() {
    // Monthly registrations chart
    const monthlyData = @json($monthly_registrations);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const chartData = months.map((month, index) => monthlyData[index + 1] || 0);

    const ctx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Jumlah Acara',
                data: chartData,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush