class TimeEditor {
    constructor() {
        this.pattern = /(\d+)[hjm]/g; // Regex to match '2h', '3j', '6m'
    }

    /**
     * Validates the input string format.
     * @param {string} input - Time input string, e.g., `2h 3j 6m`.
     * @returns {boolean} True if valid, false otherwise.
     */
    isValidInput(input) {
        // Remove spaces and match the full string with the pattern
        let cleanedInput = input.replace(/\s+/g, '');
        cleanedInput = cleanedInput.trim();
        if(cleanedInput == '')
        {
            return true;
        }
        const fullPattern = /^(\d+[hjm])+(?:\s*\d+[hjm])*$/; // Validate full input
        return fullPattern.test(cleanedInput);
    }

    /**
     * Converts time from the format `2h 3j 6m` to total minutes.
     * @param {string} input - Time input string, e.g., `2h 3j 6m`.
     * @returns {number} Total time in minutes.
     */
    toMinutes(input) {
        if (!this.isValidInput(input)) {
            throw new Error("Invalid input format. Expected format: '2h 3j 6m'.");
        }

        let totalMinutes = 0;
        let match;
        while ((match = this.pattern.exec(input)) !== null) {
            const value = parseInt(match[1], 10);
            const unit = match[0].slice(-1);

            if (unit === 'h') {
                totalMinutes += value * 24 * 60; // Days to minutes
            } else if (unit === 'j') {
                totalMinutes += value * 60; // Hours to minutes
            } else if (unit === 'm') {
                totalMinutes += value; // Minutes
            }
        }
        return totalMinutes;
    }

    /**
     * Converts total minutes back to the format `2h 3j 6m`.
     * @param {number} minutes - Total minutes.
     * @returns {string} Time in the format `2h 3j 6m`.
     */
    fromMinutes(minutes) {
        const days = Math.floor(minutes / (24 * 60));
        minutes %= 24 * 60;

        const hours = Math.floor(minutes / 60);
        minutes %= 60;

        const parts = [];
        if (days > 0) 
        {
            parts.push(`${days}h`);
        }
        if (hours > 0) 
        {
            parts.push(`${hours}j`);
        }
        if (minutes > 0) 
        {
            parts.push(`${minutes}m`);
        }
        return parts.join(' ');
    }
}