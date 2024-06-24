import yaml
from debug import inject_mode


def _read_config():
    with open('config.yaml', 'r') as file:
        return yaml.safe_load(file)


@inject_mode()
def initialize(driver, debug_mode=None):
    config = _read_config()
    debug_mode.register_observer(driver)
    if config['debug']:
        debug_mode.set_on()
