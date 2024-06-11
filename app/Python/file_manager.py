import os
import sys
import glob
import pandas as pd
import random

def shutdown_program():
    sys.exit()

class FileManager:
    def __init__(self, user_id):
        self.this_folder = os.path.dirname(__file__)
        self.input_files_path = os.path.join(self.this_folder, 'input_files', str(user_id))
        self.input_arhive_file = self.input_files_path + '.zip'
        self.datasets_path = os.path.join(self.this_folder, 'output_files', str(user_id))
        self.models_path = os.path.join(self.this_folder, 'models', str(user_id))
        self.model_file_path = os.path.join(self.models_path, 'model.keras')
        self.scaler_file_path = os.path.join(self.models_path, 'scaler.pickle')
        self.file = None
        self.index = -1
        if not self.check_path(self.models_path):
            self.recursive_create_folders(self.models_path)
    
    def recursive_create_folders(self, path):
        if not os.path.exists(path):
            os.makedirs(path, exist_ok=True)

    def check_path(self, path):
        if not os.path.exists(path):
            return False
        return True

    def get_new_model_path(self):
        self.index += 1
        yield os.path.join(self.models_path, f'model_{self.index}.h5')
    
    def load_datasets(self, min_length=1080):
        dataframes = []
        if not self.check_path(self.datasets_path):
            self.recursive_create_folders(self.datasets_path)
            shutdown_program()
        csv_files = glob.glob(self.datasets_path + '/*.csv')
        for file in csv_files:
            dataframe = pd.read_csv(file)
            dataframe.drop('datetime', axis=1, inplace=True)
            dataframes.append(dataframe)
        dataframes.sort(key=lambda x: len(x), reverse=True)
        return [df for df in dataframes if len(df) >= min_length]
    
    def split_datasets(self, min_length=1080):
        dataframes = self.load_datasets(min_length)
        random_index = random.randint(0, len(dataframes) - 1)
        random_dataset = dataframes.pop(random_index)
        return random_dataset, dataframes
    
    def get_datasets_batch_generator(self, min_length=1080, batch_length=256):
        dataframes = self.load_datasets(min_length)
        for dataset in dataframes:
            for i in range(0, len(dataset), batch_length):
                yield dataset[i:i + batch_length]
    
    def get_datasets(self, min_length=1080):
        return self.load_datasets(min_length)