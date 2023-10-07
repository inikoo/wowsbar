// Jump the view to the an element
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
          const [key, value] = property.split(':').map(item => item.trim());
          if (key && value) {
            ruleStyles[key] = value;
          }
        });
        selectorList.forEach(selector => {
          styles[selector.trim()] = ruleStyles;
        });
      }
    });
    return styles;
  }