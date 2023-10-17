export const loadCss = (cssString: string) => {
    const styles = {};
    const rules = cssString.split('}');
    rules.forEach(rule => {
        const [selectors, declaration] = rule.split('{');
        if (selectors && declaration) {
            const selectorList = selectors.trim().split(',');
            const properties = declaration.split(';').filter(prop => prop.trim() !== '');
            const ruleStyles = {};
            properties.forEach(property => {
                const [key, ...valueParts] = property.split(':').map(item => item.trim());
                const value = valueParts.join(':'); // Reconstruct the value by joining parts after splitting by ':'
                if (key && value) {
                    // Handle special characters like parentheses in property values
                    const escapedValue = value.replace(/\\(['"])/g, "\\$1");
                    ruleStyles[key] = escapedValue;
                }
            });
            selectorList.forEach(selector => {
                const trimmedSelector = selector.trim();
                if (trimmedSelector.startsWith('.')) {
                    // Handle class selectors
                    const className = trimmedSelector.slice(1);
                    styles[`.${className}`] = ruleStyles;
                } else if (trimmedSelector.startsWith('#')) {
                    // Handle ID selectors
                    const id = trimmedSelector.slice(1);
                    styles[`#${id}`] = ruleStyles;
                }
            });
        }
    });
    return styles;
};
