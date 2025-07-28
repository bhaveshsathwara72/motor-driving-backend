@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="container-fluid">
    <!-- Info boxes -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Users</span>
                    <span class="info-box-number">{{ $totalUsers ?? 0 }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-tasks"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Quizzes</span>
                    <span class="info-box-number">{{ $totalQuizzes ?? 0 }}</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-user-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Active Players Today</span>
                    <span class="info-box-number">{{ $activePlayers ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Quiz Attempts Graph -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Weekly Quiz Attempts</h3>
                    <div class="card-tools">
                        <input type="week" class="form-control" id="weekSelector" value="{{ now()->format('Y-\WW') }}">
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="quizAttemptsChart" style="min-height: 250px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    let chartInstance = null;

    function getWeekDates(weekValue) {
        const [year, week] = weekValue.split('-W');
        const firstDay = new Date(year, 0, 1);
        const day = firstDay.getDay();
        const dayOffset = (day <= 4 ? day - 1 : day - 8);
        const monday = new Date(firstDay.setDate(firstDay.getDate() - dayOffset + (week - 1) * 7));

        const days = [];
        for (let i = 0; i < 7; i++) {
            const d = new Date(monday);
            d.setDate(monday.getDate() + i);
            days.push(d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short' }));
        }
        return days;
    }

    function createChart(labels, data) {
        const config = {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Quiz Attempts',
                    data: data,
                    fill: false,
                    borderColor: '#3b82f6',
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#3b82f6'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#3b82f6',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        };

        if (chartInstance) {
            chartInstance.destroy();
        }
        const ctx = document.getElementById('quizAttemptsChart').getContext('2d');
        chartInstance = new Chart(ctx, config);
    }

    function updateChartForWeek(weekValue) {
        // Show loading state
        document.getElementById('quizAttemptsChart').style.opacity = '0.5';
        
        // Fetch data for the selected week using AJAX
        fetch(`/api/quiz-attempts?week=${weekValue}`)
            .then(response => response.json())
            .then(data => {
                const weekDates = getWeekDates(weekValue);
                createChart(weekDates, data.attempts);
                document.getElementById('quizAttemptsChart').style.opacity = '1';
            })
            .catch(error => {
                console.error('Error fetching quiz attempts:', error);
                document.getElementById('quizAttemptsChart').style.opacity = '1';
            });
    }

    // Initialize chart with current week's data
    document.addEventListener('DOMContentLoaded', () => {
        const weekSelector = document.getElementById('weekSelector');
        updateChartForWeek(weekSelector.value);

        // Add week selector handler
        weekSelector.addEventListener('change', (e) => {
            updateChartForWeek(e.target.value);
        });
    });
</script>
@stop