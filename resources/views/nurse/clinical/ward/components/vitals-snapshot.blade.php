<!-- SNAPSHOTS ROW -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <!-- Latest Vitals -->
    <div class="card-enterprise">
        <div class="p-4">
            <h3 class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide mb-2">Current Vitals</h3>
            @if($vitals)
            <div class="grid grid-cols-2 gap-2 text-sm">
                <div><span class="text-slate-500">BP:</span> <span class="font-mono font-semibold text-slate-800">{{ $vitals['bp'] ?? '--' }}</span></div>
                <div><span class="text-slate-500">Temp:</span> <span class="font-mono font-semibold text-slate-800">{{ $vitals['temp'] ?? '--' }}°C</span></div>
                <div><span class="text-slate-500">HR:</span> <span class="font-mono font-semibold text-slate-800">{{ $vitals['hr'] ?? '--' }}</span></div>
                <div><span class="text-slate-500">O2:</span> <span class="font-mono font-semibold text-emerald-600">{{ $vitals['o2'] ?? '--' }}%</span></div>
            </div>
            <div class="text-[10px] text-gray-400 mt-2 text-right">
                @if($latestLog && isset($latestLog->data['bp']))
                {{ $latestLog->created_at->diffForHumans() }}
                @else
                Admission Vitals ({{ $admission->admission_date->format('M d H:i') }})
                @endif
            </div>
            @else
            <div class="text-gray-400 text-sm italic">No vitals recorded yet.</div>
            @endif
        </div>
    </div>

    <!-- Treatment Plan Summary -->
    <div class="card-enterprise">
        <div class="p-4">
            <h3 class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide mb-2">Care Plan Focus</h3>
            @if($admission->nursingCarePlans)
            <div class="text-sm font-bold text-slate-800 mb-1 line-clamp-1">{{ $admission->nursingCarePlans->diagnosis }}</div>
            <ul class="list-disc list-inside text-xs text-gray-600">
                @foreach(array_slice($admission->nursingCarePlans->interventions ?? [], 0, 2) as $goal)
                <li>{{ $goal }}</li>
                @endforeach
            </ul>
            @else
            <div class="text-gray-400 text-sm italic">No active plan.</div>
            @endif
        </div>
    </div>
</div>
