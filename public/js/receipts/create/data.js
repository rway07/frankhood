/**
 * @file receipts/create/data.js
 * @author kain - rway07@gmail.com
 */

export const Action = {
    Add: 0,
    Remove: 1,
};

export const QuotaChangeDirection = {
    AlternativeToNormal: 1,
    NormalToAlternative: 0,
};

export const QuotaTypes = {
    Alternative: 1,
    Normal: 0,
};

export class UIStatus {
    constructor() {
        this.edit = false;
        this.groupLoading = false;
        this.quotaTypeChanged = false;
        this.quotaType = QuotaTypes.Normal;
        this.direction = QuotaChangeDirection.NormalToAlternative;
    }

    enableEditMode() {
        this.edit = true;
    }

    disableEditMode() {
        this.edit = false;
    }

    isEditModeActive() {
        return this.edit;
    }

    setGroupLoading(groupLoading) {
        this.groupLoading = groupLoading;
    }

    isGroupLoading() {
        return this.groupLoading;
    }

    setQuotaType(quotaType) {
        this.quotaType = parseInt(quotaType, 10);
    }

    getQuotaType() {
        return this.quotaType;
    }

    isQuotaAlternate() {
        return this.quotaType === QuotaTypes.Alternative;
    }

    setQuotaTypeChanged(mode) {
        this.quotaTypeChanged = mode;
    }

    isQuotaTypeChanged() {
        return this.quotaTypeChanged;
    }

    setDirectionAlternateToNormal() {
        this.direction = 1;
    }

    setDirectionNormalToAlternate() {
        this.direction = 0;
    }

    getDirection() {
        return this.direction;
    }
}
