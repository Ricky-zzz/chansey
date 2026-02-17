<script>
    function clinicalChart() {
        return {
            logType: 'Notes',
            orderId: null,
            medName: '',
            medQty: 0,

            transferOrderId: null,
            transferInstruction: '',
            transferStations: [],
            transferRawBeds: [],
            selectedTargetStation: '',
            viewLogData: {},

            init() {
                this.transferStations = JSON.parse(
                    this.$el.dataset.stations
                );
                this.transferRawBeds = JSON.parse(
                    this.$el.dataset.beds
                );
            },

            filteredTransferBeds() {
                if (!this.selectedTargetStation) return [];
                return this.transferRawBeds.filter(
                    bed => bed.station_id == this.selectedTargetStation
                );
            },

            openLogModal(id, type, medName = '', medQty = 0) {
                this.orderId = id;
                this.logType = type || 'Notes';
                this.medName = medName;
                this.medQty = parseInt(medQty) || 0;
                document.getElementById('log_modal').showModal();
            },

            openLabModal(id, instruction) {
                this.labOrderId = id;
                this.labInstruction = instruction;
                document.getElementById('lab_modal').showModal();
            },

            openTransferModal(id, instruction) {
                this.transferOrderId = id;
                this.transferInstruction = instruction;
                this.selectedTargetStation = '';
                document.getElementById('transfer_modal').showModal();
            },

            viewLog(logObject) {
                this.viewLogData = logObject;
                document.getElementById('view_log_modal').showModal();
            },
        }
    }
</script>
