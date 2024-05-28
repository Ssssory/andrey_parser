import yaml
from driver import set_debug


def _read_config():
    with open('config.yaml', 'r') as file:
        return yaml.safe_load(file)


def initialize():
    config = _read_config()
    set_debug(config['debug'])
