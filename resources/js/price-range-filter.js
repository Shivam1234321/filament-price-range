class PriceRangeFilter {
    constructor(element, options = {}) {
        this.element = element;
        this.options = {
            min: options.min || 0,
            max: options.max || 10000,
            step: options.step || 1,
            fromLabel: options.fromLabel || 'FROM',
            toLabel: options.toLabel || 'TO',
            ...options
        };
        
        this.minValue = this.options.min;
        this.maxValue = this.options.max;
        this.isDragging = false;
        this.dragTarget = null;
        
        this.init();
    }

    init() {
        this.createSlider();
        this.bindEvents();
        this.updateDisplay();
    }

    createSlider() {
        const sliderContainer = this.element.querySelector('.price-range-slider-container');
        if (!sliderContainer) return;

        // Create slider track
        const track = document.createElement('div');
        track.className = 'price-range-track';
        
        // Create active range
        const activeRange = document.createElement('div');
        activeRange.className = 'price-range-active';
        
        // Create min handle
        const minHandle = document.createElement('div');
        minHandle.className = 'price-range-handle price-range-handle-min';
        minHandle.setAttribute('data-handle', 'min');
        
        // Create max handle
        const maxHandle = document.createElement('div');
        maxHandle.className = 'price-range-handle price-range-handle-max';
        maxHandle.setAttribute('data-handle', 'max');
        
        track.appendChild(activeRange);
        track.appendChild(minHandle);
        track.appendChild(maxHandle);
        
        sliderContainer.appendChild(track);
        
        this.track = track;
        this.activeRange = activeRange;
        this.minHandle = minHandle;
        this.maxHandle = maxHandle;
    }

    bindEvents() {
        // Mouse events
        this.minHandle.addEventListener('mousedown', (e) => this.startDrag(e, 'min'));
        this.maxHandle.addEventListener('mousedown', (e) => this.startDrag(e, 'max'));
        
        // Touch events
        this.minHandle.addEventListener('touchstart', (e) => this.startDrag(e, 'min'));
        this.maxHandle.addEventListener('touchstart', (e) => this.startDrag(e, 'max'));
        
        // Track click
        this.track.addEventListener('click', (e) => this.handleTrackClick(e));
        
        // Global events
        document.addEventListener('mousemove', (e) => this.handleDrag(e));
        document.addEventListener('mouseup', () => this.endDrag());
        document.addEventListener('touchmove', (e) => this.handleDrag(e));
        document.addEventListener('touchend', () => this.endDrag());
    }

    startDrag(e, handle) {
        e.preventDefault();
        this.isDragging = true;
        this.dragTarget = handle;
        
        if (handle === 'min') {
            this.minHandle.classList.add('active');
        } else {
            this.maxHandle.classList.add('active');
        }
    }

    handleDrag(e) {
        if (!this.isDragging) return;
        
        e.preventDefault();
        
        const clientX = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
        const rect = this.track.getBoundingClientRect();
        const percentage = Math.max(0, Math.min(1, (clientX - rect.left) / rect.width));
        const value = Math.round(this.options.min + percentage * (this.options.max - this.options.min));
        const steppedValue = Math.round(value / this.options.step) * this.options.step;
        
        if (this.dragTarget === 'min') {
            this.setMinValue(Math.min(steppedValue, this.maxValue));
        } else {
            this.setMaxValue(Math.max(steppedValue, this.minValue));
        }
    }

    endDrag() {
        if (!this.isDragging) return;
        
        this.isDragging = false;
        this.minHandle.classList.remove('active');
        this.maxHandle.classList.remove('active');
        this.dragTarget = null;
        
        this.dispatchChangeEvent();
    }

    handleTrackClick(e) {
        if (this.isDragging) return;
        
        const rect = this.track.getBoundingClientRect();
        const percentage = (e.clientX - rect.left) / rect.width;
        const value = Math.round(this.options.min + percentage * (this.options.max - this.options.min));
        const steppedValue = Math.round(value / this.options.step) * this.options.step;
        
        // Determine which handle is closer
        const minDistance = Math.abs(steppedValue - this.minValue);
        const maxDistance = Math.abs(steppedValue - this.maxValue);
        
        if (minDistance < maxDistance) {
            this.setMinValue(Math.min(steppedValue, this.maxValue));
        } else {
            this.setMaxValue(Math.max(steppedValue, this.minValue));
        }
        
        this.dispatchChangeEvent();
    }

    setMinValue(value) {
        this.minValue = Math.max(this.options.min, Math.min(value, this.maxValue));
        this.updateDisplay();
    }

    setMaxValue(value) {
        this.maxValue = Math.max(this.minValue, Math.min(value, this.options.max));
        this.updateDisplay();
    }

    updateDisplay() {
        const minPercentage = ((this.minValue - this.options.min) / (this.options.max - this.options.min)) * 100;
        const maxPercentage = ((this.maxValue - this.options.min) / (this.options.max - this.options.min)) * 100;
        
        // Update handle positions
        this.minHandle.style.left = `${minPercentage}%`;
        this.maxHandle.style.left = `${maxPercentage}%`;
        
        // Update active range
        this.activeRange.style.left = `${minPercentage}%`;
        this.activeRange.style.width = `${maxPercentage - minPercentage}%`;
        
        // Update display values
        const fromDisplay = this.element.querySelector('.price-range-from-value');
        const toDisplay = this.element.querySelector('.price-range-to-value');
        
        if (fromDisplay) {
            fromDisplay.textContent = this.minValue;
        }
        if (toDisplay) {
            toDisplay.textContent = this.maxValue;
        }
        
        // Update hidden inputs
        const minInput = this.element.querySelector('input[name*="min"]');
        const maxInput = this.element.querySelector('input[name*="max"]');
        
        if (minInput) {
            minInput.value = this.minValue;
        }
        if (maxInput) {
            maxInput.value = this.maxValue;
        }
    }

    dispatchChangeEvent() {
        const event = new CustomEvent('priceRangeChange', {
            detail: {
                min: this.minValue,
                max: this.maxValue
            }
        });
        this.element.dispatchEvent(event);
    }

    getValues() {
        return {
            min: this.minValue,
            max: this.maxValue
        };
    }

    setValues(min, max) {
        this.setMinValue(min);
        this.setMaxValue(max);
    }
}

// Initialize all price range filters on the page
document.addEventListener('DOMContentLoaded', function() {
    const priceRangeElements = document.querySelectorAll('[data-price-range-filter]');
    
    priceRangeElements.forEach(element => {
        const options = {
            min: parseInt(element.dataset.min) || 0,
            max: parseInt(element.dataset.max) || 10000,
            step: parseInt(element.dataset.step) || 1,
            fromLabel: element.dataset.fromLabel || 'FROM',
            toLabel: element.dataset.toLabel || 'TO'
        };
        
        new PriceRangeFilter(element, options);
    });
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PriceRangeFilter;
}
